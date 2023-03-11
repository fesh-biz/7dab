<template>
  <div class="row justify-center q-px-sm">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
      <q-linear-progress :class="{'q-mt-md': !$q.platform.is.mobile}" v-if="isFetching" indeterminate/>

      <post
          v-if="!isFetching"
          :post="post"
      />
    </div>
  </div>
</template>

<script>
import Post from 'components/content/Post'
import PostApi from 'src/plugins/api/post'
import Cache from 'src/plugins/cache/cache'

import PostModel from 'src/models/content/post'

export default {
  name: 'PostPage',

  components: {
    Post
  },

  data () {
    return {
      isFetching: true,
      postApi: new PostApi(),
      post: null,
      postId: null,
      cache: new Cache(),
      isReady: false
    }
  },

  async created () {
    this.postId = this.$route.params.id
    this.post = PostModel.query().withAll().find(this.postId)

    if (!this.post) {
      this.fetchPost()
    } else {
      window.document.title = this.post.title + ` - ${this.$t('terevenky')}`

      if (!this.cache.getEntityCache('posts', this.postId)) {
        await this.cache.insertEntityCache('posts', this.postId)
      }

      this.isFetching = false

      await this.postApi.incrementViews(this.postId)
    }
  },

  methods: {
    fetchPost () {
      this.postApi.fetchPost(this.postId)
        .then(async res => {
          const post = res.data.data
          await PostModel.insert({
            data: post
          })
          this.post = PostModel.query().withAll().find(post.id)

          this.isFetching = false

          window.document.title = post.title + ` - ${this.$t('terevenky')}`
        })
    }
  }
}
</script>
