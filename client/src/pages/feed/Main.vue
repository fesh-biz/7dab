<template>
  <div>
    <q-linear-progress :class="{'q-mt-md': !$q.platform.is.mobile}" v-if="isFetchingPosts" indeterminate/>

    <post
      v-for="(post, index) in posts"
      :key="'post' + index"
      :post="post"
    />

    <q-banner dusk="main-no-more-posts" rounded v-if="isLast" class="text-center">
      {{ $t('there_is_no_new_posts') }}
    </q-banner>

    <q-linear-progress
      class="q-mb-xl"
      dusk="main-new-posts-loading"
      v-if="isFetchingPosts && posts.length"
      indeterminate
    />
  </div>
</template>

<script>
import PostModel from 'src/models/content/post'
import Post from 'components/content/Post'
import PostApi from 'src/plugins/api/post'
import Scroll from 'src/plugins/tools/scroll'
import Cache from 'src/plugins/cache/cache'

export default {
  name: 'FeedMain',

  components: { Post },

  data () {
    return {
      isFetchingPosts: true,
      cache: new Cache(),
      postApi: new PostApi(),
      scroll: new Scroll(),
      isPrevRequestSuccess: true,
      posts: [],
      isLast: false
    }
  },

  async created () {
    if (!this.cache.hasCacheForCurrentPage('posts')) {
      await this.fetchPosts()
    } else {
      this.posts = this.getPosts()
      this.isFetchingPosts = false
    }
  },

  mounted () {
    window.addEventListener('scroll', this.maybeFetchNextPosts)
  },

  methods: {
    scrollToPost (postAnchor) {
      const el = document.getElementById(postAnchor)

      if (!el) {
        setTimeout(() => {
          this.scrollToPost(postAnchor)
        }, 100)
      } else {
        setTimeout(() => {
          el.scrollIntoView({ behavior: 'smooth' })
        }, 100)
      }
    },

    async maybeFetchNextPosts () {
      const pagination = await this.cache.getPagination('posts')
      this.isLast = pagination.is_last

      if (
        !this.isFetchingPosts &&
        this.scroll.isScrollBottom(500) &&
        !this.isLast
      ) {
        await this.fetchPosts()
      }
    },

    getPosts () {
      const postIds = this.cache.getCurrentPageCacheIds('posts')

      return PostModel.query().withAll()
        .whereIdIn(postIds)
        .orderBy('id', 'desc')
        .all()
    },

    async fetchPosts () {
      if (!this.isPrevRequestSuccess) return

      this.isFetchingPosts = true
      this.isPrevRequestSuccess = false

      const pagination = await this.cache.getPagination('posts')

      this.postApi.fetchPosts(pagination.page)
        .then(async res => {
          await PostModel.insert({
            data: res.data.data
          })

          this.posts = this.getPosts()
          const pagination = await this.cache.getPagination('posts')
          this.isLast = pagination.is_last

          this.isFetchingPosts = false
          this.isPrevRequestSuccess = true
        })
        .catch(() => {
          this.isFetchingPosts = false
        })
    }
  },

  beforeDestroy () {
    window.removeEventListener('scroll', this.maybeFetchNextPosts)
  }
}
</script>
