<template>
  <div
    ref="mediaField"
    class="media-field flex justify-center"
    :class="{error: errorMessage}"
  >
    <!-- Change Media -->
    <div style="width: 100%">
      <icon-with-tooltip
        class="cursor-pointer"
        size="2rem"
        icon="change_circle"
        :tooltip="$t('change_image')"
      />
    </div>

    <q-linear-progress
      v-if="progress !== null"
      size="10px"
      :value="progress"
      color="positive"
      rounded
    />

    <!-- Add Media -->
    <q-icon
      class="cursor-pointer"
      style="background-color: white"
      size="10rem"
      color="blue-grey-2"
      name="cloud_upload"
      @click="addMedia"
    />

    <!-- Error Message -->
    <span v-if="errorMessage" style="color: red; width: 100%">{{ errorMessage }}</span>
  </div>
</template>

<script>
import IconWithTooltip from 'components/common/IconWithTooltip'
import MediaApi from 'src/plugins/api/media-api'

const allowedFileTypes = [
  'image/jpeg', 'image/gif', 'image/webp', 'image/png',
  'video/webm', 'video/mp4', 'video/avi'
]

const fileChunkSize = 1024 * 1024 * 2 // 2 Mb

export default {
  name: 'MediaField',
  components: { IconWithTooltip },
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
      mediaApi: new MediaApi(),
      progress: null,
      fileChunkSize: fileChunkSize
    }
  },

  methods: {
    addMedia () {
      this.$emit('input')
      const input = document.createElement('input')
      input.type = 'file'
      input.multiple = true

      input.onchange = (e) => {
        this.handleFiles(e.target.files)
      }

      input.click()
    },

    handleFiles (files) {
      files = this.validateFiles(files)

      if (!files.length) {
        this.$q.notify({
          message: 'Невірний формат файла або файлів.',
          color: 'negative',
          position: 'center'
        })
      }

      for (const fileIndex in files) {
        const file = files[fileIndex]
        if (fileIndex < 1) {
          this.uploadFile(file)
        }
      }
    },

    async uploadFile (file) {
      const checkType = await this.mediaApi.checkFileType(file)
      console.log('checkType', checkType)

      console.log('need to chunk', file.size > this.fileChunkSize)

      // let chunks = null
      // if (file.size > this.minFileSizeToChunk) {
      //   chunks = []
      //   let start = 0
      //   const end = file.size
      //   while (start < end) {
      //     const endOffset = start + this.fileChunkSize
      //     chunks.push(file.slice(start, endOffset))
      //     start = endOffset
      //   }
      //
      //   this.mediaApi.upload(chunks[0], (percentage) => {
      //     this.progress = percentage !== 100 ? percentage / 100 : null
      //   })
      // }

      // const firstBytes = file.slice(0, 100)
      // await this.mediaApi.upload(firstBytes, (percentage) => {
      //   this.progress = percentage !== 100 ? percentage / 100 : null
      // })
    },

    validateFiles (files) {
      const res = []

      for (const file of files) {
        console.log('file.type', file.type)
        if (allowedFileTypes.includes(file.type)) {
          res.push(file)
        }
      }

      return res
    }
  }
}
</script>
