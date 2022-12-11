<template>
  <div
    ref="wrapper"
    class="flex justify-center"
    :class="{error: errorMessage}"
  >
    <!-- Change Image -->
    <div v-if="isImageAdded || (content && content.url)" style="width: 100%;">
      <icon-with-tooltip
        class="cursor-pointer"
        size="2rem"
        icon="change_circle"
        :tooltip="$t('change_image')"
        @click="addImages"
      />
    </div>

    <!-- Add Image -->
    <q-icon
      class="cursor-pointer"
      style="background-color: white"
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
      @input="input"
    />

    <!-- Error Message -->
    <span v-if="errorMessage" style="color: red; width: 100%">{{ errorMessage }}</span>
  </div>
</template>

<script>
import IconWithTooltip from 'components/common/IconWithTooltip'
import PostEditor from 'src/plugins/editor/post'

export default {
  name: 'ImageField',

  components: {
    IconWithTooltip
  },

  props: {
    content: {
      type: Object,
      default: null
    },
    order: {
      type: Number,
      required: true
    },
    errorMessage: {
      type: [String]
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

  async mounted () {
    this.wrapperWidth = this.$refs.wrapper.offsetWidth

    if (!this.content) {
      this.addImages()
      return
    }

    if (this.content.file) {
      this.drawImage(this.content.file)
      return
    }

    if (this.content.url) {
      this.drawImageFromUrl(this.content.url)
    }
  },

  methods: {
    input () {
      this.$emit('input')
    },

    addImages () {
      this.$emit('input')
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

    async drawImageFromUrl (url) {
      const response = await fetch(url)
      const data = await response.blob()
      const metadata = {
        type: 'image/jpeg'
      }

      let fileName = url
      fileName = fileName.substr(fileName.lastIndexOf('/') + 1)
      const file = new File([data], fileName, metadata)
      this.drawImage(file)
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
          message = this.$t('wrong_image_file_allowed_types') + ': jpg, jpeg, png'
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
