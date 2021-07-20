<template>
  <div ref="image" style="width: 100%" class="flex justify-center">
    <q-img
      :src="getImageUrl()"
      spinner-color="white"
      :style="{
        margin: '10px 0 15px 0',
        borderRadius: '5px',
        width: isImageWiderThanWrapper ? '100%' : postImage.width + 'px'
      }"
    />

    <div
      class="font-lobster q-mb-lg text-center"
      style="font-size: 20px; background-color: #eee; border-radius: 5px"
      v-if="postImage.title"
    >
      {{ postImage.title }}
    </div>
  </div>
</template>

<script>
import PostImageModel from 'src/models/content/PostImageModel'

export default {
  name: 'PostImage',

  props: {
    postImage: {
      type: PostImageModel,
      required: true
    }
  },

  data () {
    return {
      isImageWiderThanWrapper: null
    }
  },

  mounted () {
    this.isImageWiderThanWrapper = this.$refs.image.offsetWidth < this.postImage.width
  },

  methods: {
    getImageUrl () {
      const originalStorage = '/storage/post-images/original'

      return `${originalStorage}/${this.postImage.filename}`
    }
  }
}
</script>
