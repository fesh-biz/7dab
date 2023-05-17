<template>
  <div class="q-mt-md">
    <stats :user-id="userId"/>

    <post-feed :url="`users/${userId}/posts`" :params="{uid: userId}"/>
  </div>
</template>

<script>
import Stats from 'components/user/Stats'
import UserApi from 'src/plugins/api/user'
import User from 'src/models/user/user'
import PostFeed from 'components/common/PostsFeed'

export default {
  name: 'Index',

  components: { PostFeed, Stats },

  data () {
    return {
      userApi: new UserApi()
    }
  },

  computed: {
    userId () {
      return parseInt(this.$route.params.id)
    },

    user () {
      return User.query().find(this.userId)
    }
  },

  async created () {
    if (!this.user) {
      const res = await this.userApi.fetchUser(this.userId)
      await User.insert({ data: res.data })
    }

    window.document.title = `${this.$t('user')}: ${this.user.login} - ${this.$t('terevenky')}`
  }
}
</script>
