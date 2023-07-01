<template>
  <div>
    Заплановано: {{ totalPosts }} (на {{ totalPosts * 4 }} годин)
  </div>
</template>

<script>
import Api from 'src/plugins/api/api'

export default {
  name: 'FakeUserPostsInfo',

  data () {
    return {
      api: new Api(),
      totalPosts: 0,
      expanded: false
    }
  },

  created () {
    this.fetchFakeUsersPostsAmount()
  },

  methods: {
    fetchFakeUsersPostsAmount () {
      this.api.get('/admin/posts/fake-users-posts', null, null, true)
        .then(res => {
          this.totalPosts = res.data
        })
    }
  }
}
</script>
