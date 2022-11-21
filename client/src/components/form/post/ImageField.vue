<template>
  <div ref="wrapper" class="flex justify-center">
    <!-- Add Image -->
    <q-icon
      class="cursor-pointer"
      size="10rem"
      color="blue-grey-2"
      name="add_a_photo"
      v-if="!isImageAdded"
      @click="addImages"
    />

    <canvas :width="this.wrapperWidth" height="0" ref="canvas"/>
  </div>
</template>

<script>
import PostEditor from 'src/plugins/editor/post'

export default {
  name: 'ImageField',

  props: {
    content: {
      type: Object,
      default: null
    },
    order: {
      type: Number,
      required: true
    }
  },

  data () {
    return {
      wrapperWidth: 0,
      isImageAdded: false,
      postEditor: new PostEditor()
    }
  },

  mounted () {
    this.wrapperWidth = this.$refs.wrapper.offsetWidth

    if (!this.content) {
      this.addImages()
      return
    }

    if (this.content.file) this.drawImage(this.content.file)
  },

  methods: {
    addImages () {
      const input = document.createElement('input')
      input.type = 'file'
      input.multiple = true

      input.onchange = (e) => {
        this.handleImages(e.target.files)
      }

      input.click()
    },

    handleImages (files) {
      this.drawImage(files[0])

      const images = []
      for (let i = 0; i < files.length; i++) {
        if (i > 0) images.push(files[i])
      }

      this.postEditor.addSections('image', this.order, images)
    },

    drawImage (file) {
      const canvas = this.$refs.canvas
      const ctx = canvas.getContext('2d')

      const reader = new FileReader()
      reader.onload = (event) => {
        const img = new Image()
        img.onload = () => {
          let width = img.width
          let height = img.height
          if (width > this.wrapperWidth) {
            if (width > height) {
              height = height * (this.wrapperWidth / width)
            } else {
              height = height * (width / this.wrapperWidth)
            }

            width = this.wrapperWidth
          }

          canvas.width = width
          canvas.height = height
          ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, width, height)

          this.isImageAdded = true
        }
        img.src = event.target.result
      }
      reader.readAsDataURL(file)
    }
  }
}
</script>
