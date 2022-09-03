<template>
  <div v-html="data.body"></div>
</template>

<script>
import PostTextModel from 'src/models/content/PostTextModel'

export default {
  name: 'PostText',

  props: {
    data: {
      type: PostTextModel,
      required: true
    }
  }
}
</script>
