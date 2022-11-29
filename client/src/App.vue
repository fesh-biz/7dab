<template>
  <div id="q-app">
    <router-view/>
  </div>
</template>
<script>
import MeCookies from 'src/plugins/cookies/me'
import Token from 'src/plugins/cookies/token'
import UserApi from 'src/plugins/api/user'
import Me from 'src/models/user/me'

export default {
  name: 'App',

  data () {
    return {
      tokenCookies: new Token(),
      userApi: new UserApi(),
      meCookies: new MeCookies()
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
      this.meCookies.delete()
    } else {
      Me.create({
        data: this.meCookies.get()
      })
    }
  }
}
</script>
