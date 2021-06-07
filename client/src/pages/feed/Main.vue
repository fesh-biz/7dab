<template>
  <div class="row justify-center">
    <div class="col-sm-12 col-xs-12 col-md-10 col-lg-9 col-xl-7">
      <q-linear-progress v-if="fetching.posts" indeterminate/>

      <post
        v-for="(post, index) in posts"
        :key="'post' + index"
        :item="post"
      />

      <q-banner dusk="main-no-more-posts" rounded v-if="isLastFetched" class="text-center">
        {{ $t('there_is_no_new_posts') }}
      </q-banner>

      <q-linear-progress dusk="main-new-posts-loading" v-if="fetching.posts && posts.length" indeterminate/>
    </div>
  </div>
</template>

<script>
import PostModel from 'src/models/content/PostModel'
import Post from 'components/content/Post'
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
      currentPage: 0
    }
  },

  computed: {
    posts () {
      return PostModel.query().withAll().all()
    }
  },

  created () {
    this.fetchPosts(true)
  },

  mounted () {
    window.addEventListener('scroll', this.maybeFetchNextPosts)
  },

  methods: {
    maybeFetchNextPosts () {
      if (!this.fetching.posts && isScrollBottom(500) && !this.isLastFetched) {
        this.fetchPosts()
      }
    },

    fetchPosts (isFirstTime) {
      if (this.isLastFetched) {
        return
      }

      this.fetching.posts = true
      this.$get(`/content/posts?page=${++this.currentPage}`)
        .then(async res => {
          if (isFirstTime) {
            await PostModel.create({
              data: res.data.data
            })
          } else {
            await PostModel.insert({
              data: res.data.data
            })
          }
          this.isLastFetched = res.data.meta.is_last

          this.$nextTick(() => {
            this.fetching.posts = false
          })
        })
        .catch(() => {
          this.fetching.posts = false
        })
    }
  }
}
</script>
