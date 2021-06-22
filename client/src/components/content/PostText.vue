<template>
  <div>
    {{ postText.body }}
  </div>
</template>

<script>
import PostTextModel from 'src/models/content/PostTextModel'

export default {
  name: 'PostText',

  props: {
    postText: {
      type: PostTextModel,
      required: true
    }
  }
}
</script>
