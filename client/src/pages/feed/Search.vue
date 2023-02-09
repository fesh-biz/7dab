<template>
  <div>
    temp
  </div>
</template>

<script>
export default {
  name: 'Search',

  data () {
    return {}
  },

  created () {
    window.document.title = this.$t('search') + ` - ${this.$t('terevenky')}`
  }
}
</script>
