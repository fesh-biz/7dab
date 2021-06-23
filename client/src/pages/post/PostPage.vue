<template>
  <div class="row justify-center q-px-sm">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
      <q-linear-progress :class="{'q-mt-md': !$q.platform.is.mobile}" v-if="!post" indeterminate/>

      <post
        v-if="post"
        :post="post"
      />
    </div>
  </div>
</template>

<script>
import Post from 'components/content/Post'

import PostModel from 'src/models/content/PostModel'

export default {
  name: 'PostPage',

  components: {
    Post
  },

  data () {
    return {
      fetching: {
        post: true
      }
    }
  },

  computed: {
    post () {
      return PostModel.query().withAll().find(this.postId)
    },

    postId () {
      return this.$route.params.id
    }
  },

  created () {
    if (!this.post) {
      this.fetchPost()
    }
  },

  methods: {
    fetchPost () {
      console.log('fetching post')
      this.$get(`content/posts/${this.postId}`)
        .then(res => {
          PostModel.insert({
            data: res.data.data
          })
        })
    }
  }
}
</script>
