<template>
  <q-avatar :size="size">
    <img v-if="src" :src="src">
    <q-icon v-if="!src" :size="size" color="grey" name="account_circle"/>
  </q-avatar>
</template>

<script>
export default {
  name: 'UserAvatar',

  props: {
    size: {
      type: String,
      default: '72px'
    },
    src: {
      type: String,
      default: null
    }
  },

  data () {
    return {}
  }
}
</script>
