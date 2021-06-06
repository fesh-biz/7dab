<template>
  <div class="row justify-center">
    <div ref="posts" class="col-sm-12 col-xs-12 col-md-10 col-lg-9 col-xl-7">
      <q-linear-progress v-if="!posts.length" indeterminate/>

      <post
        v-for="(post, index) in posts"
        :key="'post' + index"
        :item="post"
      />

      <q-linear-progress v-if="fetching.posts && posts.length" indeterminate/>
    </div>
  </div>
</template>

<script>
import SettingsModel from 'src/models/App/SettingsModel'
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
      isLastFetched: false
    }
  },

  computed: {
    posts () {
      return PostModel.query().withAll().all()
    },

    settings () {
      return SettingsModel.query().first()
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
      this.fetching.posts = true
      this.$get(`/content/posts?offset=${this.settings.feed_offset}`)
        .then(async res => {
          if (!res.data.length) {
            this.isLastFetched = true
            this.fetching.posts = false
            return
          }

          if (isFirstTime) {
            await PostModel.create({
              data: res.data
            })
          } else {
            await PostModel.insert({
              data: res.data
            })
          }

          await SettingsModel.update({
            where: this.settings.id,
            data: {
              feed_offset: this.settings.feed_offset + this.settings.feed_entries_per_request
            }
          })
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
