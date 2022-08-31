<template>
  <div ref="image" style="width: 100%" class="flex justify-center">
    <q-img
        :src="imageUrl"
        spinner-color="white"
        :style="{
        margin: '10px 0 15px 0',
        borderRadius: '5px',
        width: isImageWiderThanWrapper ? '100%' : imageWidth + 'px'
      }"
    />

    <div
        class="font-lobster q-mb-lg text-center"
        style="font-size: 20px; background-color: #eee; border-radius: 5px"
        v-if="data.title"
    >
      {{ data.title }}
    </div>
  </div>
</template>

<script>
import PostImageModel from 'src/models/content/PostImageModel'

export default {
  name: 'PostImage',

  props: {
    data: {
      type: PostImageModel,
      required: true
    }
  },

  computed: {
    isDesktop () {
      return this.$q.platform.is.desktop
    },

    imageUrl () {
      const isDesktop = this.$q.platform.is.desktop

      if (isDesktop) return this.data.desktop_file_path || this.data.original_file_path

      return this.data.mobile_file_path || this.data.original_file_path
    },

    imageWidth () {
      const data = this.data.imageData

      if (this.isDesktop) {
        if (this.data.desktop_file_path) return data.desktop.width

        return data.original.width
      } else {
        if (this.data.mobile_file_path) return data.mobile.width

        return data.original.width
      }
    }
  },

  data () {
    return {
      isImageWiderThanWrapper: null
    }
  },

  mounted () {
    this.isImageWiderThanWrapper = this.$refs.image.offsetWidth < this.data.width
  }
}
</script>
