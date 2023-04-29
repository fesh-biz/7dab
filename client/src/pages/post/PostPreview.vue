<template>
  <div>
    <q-linear-progress :class="{'q-mt-md': !$q.platform.is.mobile}" v-if="isFetching" indeterminate/>

    <post
      v-if="!isFetching"
      :post="post"
      isPostView
    />
  </div>
</template>

<script>
import Post from 'components/content/Post'
import PostApi from 'src/plugins/api/post'

import PostModel from 'src/models/content/post'

export default {
  name: 'PostView',

  components: { Post },

  data () {
    return {
      isFetching: true,
      postApi: new PostApi(),
      post: null,
      postId: null,
      isReady: false
    }
  },

  async created () {
    this.postId = this.$route.params.id

    await this.fetchPost()
    window.document.title = this.post.title + ` - ${this.$t('terevenky')}`

    this.isFetching = false
  },

  methods: {
    async fetchPost () {
      this.postApi.fetchPostPreview(this.postId)
        .then(async res => {
          const post = res.data.data
          await PostModel.insert({
            data: post
          })
          this.post = PostModel.query().withAll().find(post.id)

          this.isFetching = false
          this.maybeScrollBottom()

          window.document.title = post.title + ` - ${this.$t('terevenky')}`
        })
    }
  }
}
</script>
