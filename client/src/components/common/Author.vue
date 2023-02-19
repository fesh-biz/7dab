<template>
  <q-chip
    class="glossy"
    :color="bgColor"
    size="0.8rem"
  >
    <q-avatar>
      <img v-if="avatar" src="https://cdn.quasar.dev/img/avatar5.jpg">
      <q-icon v-if="!avatar" color="grey-8" name="account_circle" size="2.3rem" style="margin-top: -2px"/>
    </q-avatar>
    <strong style="color: #363636">{{ name }}</strong>
  </q-chip>
</template>

<script>
export default {
  name: 'Author',

  props: {
    isPostAuthor: {
      type: Boolean,
      default: true
    },
    avatar: {
      type: String,
      default: null
    },
    name: {
      type: String,
      required: true
    }
  },

  data () {
    return {
    }
  },

  computed: {
    bgColor () {
      return this.isPostAuthor ? 'light-green-11' : 'light-blue-1'
    }
  }
}
</script>
