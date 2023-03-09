<template>
  <div class="row justify-center q-px-sm">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
      <q-linear-progress :class="{'q-mt-md': !$q.platform.is.mobile}" v-if="fetching.posts" indeterminate/>

      <post
        v-for="(post, index) in posts"
        :key="'post' + index"
        :post="post"
      />

      <q-banner dusk="main-no-more-posts" rounded v-if="cache.getIsLastFetched()" class="text-center">
        {{ $t('there_is_no_new_posts') }}
      </q-banner>

      <q-linear-progress
        class="q-mb-xl"
        dusk="main-new-posts-loading"
        v-if="fetching.posts && posts.length"
        indeterminate
      />
    </div>
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
      fetching: {
        posts: false
      },
      cache: new Cache(),
      postApi: new PostApi(),
      scroll: new Scroll(),
      isPrevRequestSuccess: true
    }
  },

  computed: {
    posts () {
      return PostModel.query().withAll()
        .whereIdIn(this.cache.getPageIds('posts'))
        .orderBy('id', 'desc')
        .all()
    }
  },

  async created () {
    if (!this.cache.hasCurrentPage()) {
      this.fetchPosts(true)
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

    maybeFetchNextPosts () {
      if (
        !this.fetching.posts &&
        this.scroll.isScrollBottom(500) &&
        !this.cache.getIsLastFetched()
      ) {
        this.fetchPosts()
      }
    },

    fetchPosts () {
      if (!this.isPrevRequestSuccess) return

      this.fetching.posts = true
      this.isPrevRequestSuccess = false
      this.postApi.fetchPosts(++this.cache.getPage().currentPage)
        .then(res => {
          PostModel.insert({
            data: res.data.data
          })
          this.cache.setIsLastFetched(res.data.meta.is_last)

          this.fetching.posts = false
          this.isPrevRequestSuccess = true
        })
        .catch(() => {
          this.fetching.posts = false
        })
    }
  },

  beforeDestroy () {
    window.removeEventListener('scroll', this.maybeFetchNextPosts)
  }
}
</script>
