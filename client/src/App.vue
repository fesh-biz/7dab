<template>
  <div id="q-app">
    <router-view />
  </div>
</template>
<script>
import Me from 'src/models/user/Me'

export default {
  name: 'App',

  data () {
    return {}
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

  async created () {
    if (this.$q.cookies.get('me')) {
      await Me.create({ data: this.$q.cookies.get('me') })
    }
  }
}
</script>
