<template>
  <div class="row justify-center q-px-sm">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
      <q-linear-progress :class="{'q-mt-md': !$q.platform.is.mobile}" v-if="fetching.posts" indeterminate/>

      <post
        v-for="(post, index) in posts"
        :key="'post' + index"
        :post="post"
      />

      <q-banner dusk="main-no-more-posts" rounded v-if="isLastFetched" class="text-center">
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
import { isScrollBottom } from 'src/plugins/scroll'

export default {
  name: 'FeedMain',
  components: { Post },
  data () {
    return {
      fetching: {
        posts: false
      },
      isLastFetched: false,
      currentPage: 0,
      postApi: new PostApi()
    }
  },

  computed: {
    posts () {
      return PostModel.query().withAll().orderBy('id', 'desc').all()
    }
  },

  async created () {
    if (this.posts.length < 2) {
      await PostModel.deleteAll()
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
      if (!this.fetching.posts && isScrollBottom(500) && !this.isLastFetched) {
        this.fetchPosts()
      }
    },

    fetchPosts (isFirstTime) {
      this.fetching.posts = true
      this.postApi.fetchPosts(++this.currentPage)
        .then(res => {
          if (isFirstTime) {
            PostModel.create({
              data: res.data.data
            })
          } else {
            PostModel.insert({
              data: res.data.data
            })
          }
          this.isLastFetched = res.data.meta.is_last

          this.fetching.posts = false
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
