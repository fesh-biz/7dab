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
import Cache from 'src/plugins/cache/cache'

export default {
  name: 'App',

  data () {
    return {
      tokenCookies: new Token(),
      userApi: new UserApi(),
      meCookies: new MeCookies(),
      cache: new Cache()
    }
  },

  watch: {
    '$route' (toRoute, fromRoute) {
      this.cache.refreshCache()
      if (this.$route.meta && this.$route.meta.title) {
        window.document.title = `${this.$t(this.$route.meta.title)} - ${this.$t('terevenky')}`
      } else {
        window.document.title = this.$t('terevenky')
      }
    }
  },

  created () {
    if (this.tokenCookies.getIsExpired()) {
      this.tokenCookies.delete()
      this.meCookies.delete()
    } else if (this.meCookies.get()) {
      Me.create({
        data: this.meCookies.get()
      })
    }
  }
}
</script>
