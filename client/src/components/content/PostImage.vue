<template>
  <div>
    <div
      ref="image"
      class="flex justify-center"
    >
      <img
        :src="imageUrl"
        alt="Зображення"
        style="max-width: 100%"
        :style="{
          margin: '10px 0 15px 0',
          borderRadius: '5px'
        }"
      />
    </div>

    <!-- Image Title -->
    <div
      class="font-lobster q-mb-lg text-center"
      v-if="data.title"
    >
      {{ data.title }}
    </div>
  </div>
</template>

<script>
import PostImage from 'src/models/content/post-image'

export default {
  name: 'PostImage',

  props: {
    data: {
      type: PostImage,
      required: true
    }
  },

  data () {
    return {
      isImageWiderThanWrapper: null
    }
  },

  computed: {
    isDesktop () {
      return this.$q.platform.is.desktop
    },

    imageUrl () {
      const isDesktop = this.$q.platform.is.desktop

      if (isDesktop) return this.data.desktop_file_path || this.data.original_file_path

      return this.data.mobile_file_path || this.data.desktop_file_path || this.data.original_file_path
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

  mounted () {
    this.isImageWiderThanWrapper = this.$refs.image.offsetWidth < this.data.width
  }
}
</script>
