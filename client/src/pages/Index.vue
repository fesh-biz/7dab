<template>
  <div class="row justify-center">
    <div class="col-sm-12 col-xs-12 col-md-10 col-lg-9 col-xl-7">
      <q-linear-progress v-if="fetching.posts" indeterminate/>

      <post
        v-for="(post, index) in posts"
        :key="'post' + index"
        :item="post"
      />
    </div>
  </div>
</template>

<script>
import PostModel from 'src/models/content/PostModel'
import Post from 'components/content/Post'

export default {
  name: 'PageIndex',
  components: { Post },
  data () {
    return {
      fetching: {
        posts: false
      }
    }
  },

  computed: {
    posts () {
      return PostModel.query().withAll().all()
    }
  },

  created () {
    this.fetchPosts()
  },

  methods: {
    fetchPosts () {
      this.fetching.posts = true
      this.$get('/content/posts')
        .then(res => {
          PostModel.create({
            data: res.data
          })
          this.fetching.posts = false
        })
        .catch(() => {
          this.fetching.posts = false
        })
    }
  }
}
</script>
