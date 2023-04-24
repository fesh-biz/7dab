<template>
  <q-chip
    :color="bgColor"
    size="0.8rem"
  >
    <q-avatar>
      <img v-if="avatar" :src="avatar">
      <q-icon
        v-if="!avatar"
        :color="isPostAuthor ? 'white' : 'grey-8'"
        name="account_circle"
        size="2.3rem"
        style="margin-top: -3px"
      />
    </q-avatar>
    <strong :style="{color: isPostAuthor ? 'white' : '#363636'}">{{ name }}</strong>
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
      return this.isPostAuthor ? 'green-7' : 'light-blue-1'
    }
  }
}
</script>
