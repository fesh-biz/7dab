<template>
  <div>
    <q-linear-progress :class="{'q-mt-md': !$q.platform.is.mobile}" v-if="isFetching" indeterminate/>

    <post
      v-if="!isFetching"
      :post="post"
    />

    <div ref="commentsAnchor"></div>
    <comments v-if="post && !isPreview" :post-id="post.id"/>
  </div>
</template>

<script>
import Post from 'components/content/Post'
import PostApi from 'src/plugins/api/post'
import Cache from 'src/plugins/cache/cache'
import Comments from 'components/comment/Comments'

import PostModel from 'src/models/content/post'

export default {
  name: 'PostPage',

  components: { Post, Comments },

  data () {
    return {
      isFetching: true,
      postApi: new PostApi(),
      post: null,
      postId: null,
      cache: new Cache(),
      isReady: false,
      isPreview: false
    }
  },

  async created () {
    this.isPreview = this.$route.query.p === '1'

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

  mounted () {
    if (this.$route.params.toComments) {
      setTimeout(() => {
        this.$refs.commentsAnchor.scrollIntoView()
        window.scrollBy(0, 50)
      }, 10)
    }
  },

  methods: {
    fetchPost () {
      this.postApi.fetchPost(this.postId, this.isPreview)
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
