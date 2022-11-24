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

    <!-- Title -->
    <q-input
      v-model="title"
      style="width: 100%"
      dense
      :label="$t('add_image_label')"
      stack-label
    />
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

  computed: {
    title: {
      get () {
        return this.content?.title || ''
      },
      set (val) {
        this.postEditor.updateSection(this.order, {
          title: val
        })
      }
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
            height = height * (this.wrapperWidth / width)
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
    },

    handleImages (files) {
      const message = this.validateImages(files)

      if (message) {
        this.$q.notify({
          message: message,
          position: 'center',
          color: 'negative'
        })

        return
      }

      const firstImage = files[0]
      this.drawImage(firstImage)
      this.postEditor.updateSection(this.order, { file: firstImage })

      const images = []
      for (let i = 0; i < files.length; i++) {
        if (i > 0) images.push(files[i])
      }

      this.postEditor.addSections('image', this.order, images)
    },

    validateImages (files) {
      let message = null

      const allowedTypes = /(jpg)|(png)|(jpeg)/i
      for (let i = 0; i < files.length; i++) {
        if (!allowedTypes.test(files[i].type)) {
          message = this.$t('wrong_image_file_allowed_types') + ': jpg, png'
        }

        const maxSizeKb = 500
        if (files[i].size > maxSizeKb * 1024) {
          message = this.$t('max_allowed_filesize') + ' ' + maxSizeKb + 'Kb'
        }

        if (message) break
      }

      return message
    }
  }
}
</script>
