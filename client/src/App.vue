<template>
  <div id="q-app">
    <router-view/>
  </div>
</template>
<script>
import Me from 'src/models/user/Me'
import TokenCookies from 'src/plugins/cookies/tokenCookies'
import UserApi from 'src/plugins/api/user'

export default {
  name: 'App',

  data () {
    return {
      tokenCookies: new TokenCookies(),
      userApi: new UserApi()
    }
  },

  watch: {
    '$route' (toRoute, fromRoute) {
      if (this.$route.meta && this.$route.meta.title) {
        window.document.title = `Starter kit - ${this.$t(this.$route.meta.title)}`
      } else {
        window.document.title = 'Starter kit'
      }
    }
  },

  created () {
    if (this.tokenCookies.getIsExpired()) {
      this.tokenCookies.delete()
    }

    if (this.tokenCookies.getAuthorizationToken()) {
      this.userApi.fetchMe()
        .then(res => {
          Me.create({ data: res.data })
        })
    }
  }
}
</script>
