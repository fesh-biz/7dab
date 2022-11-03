<template>
  <div class="row justify-center q-px-sm">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
      <q-linear-progress :class="{'q-mt-md': !$q.platform.is.mobile}" v-if="!post" indeterminate/>

      <post
          :is-post-page="true"
          v-if="post"
          :post="post"
      />
    </div>
  </div>
</template>

<script>
import Post from 'components/content/Post'
import PostApi from 'src/plugins/api/post'

import PostModel from 'src/models/content/post'

export default {
  name: 'PostPage',

  components: {
    Post
  },

  data () {
    return {
      fetching: {
        post: true
      },
      postApi: new PostApi()
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
      this.postApi.fetchPost(this.postId)
        .then(res => {
          PostModel.insert({
            data: res.data.data
          })
        })
    }
  }
}
</script>
