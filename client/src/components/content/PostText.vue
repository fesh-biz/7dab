<template>
  <div v-html="data.body"></div>
</template>

<script>
import PostText from 'src/models/content/post-text'

export default {
  name: 'PostText',

  props: {
    data: {
      type: PostText,
      required: true
    }
  }
}
</script>
