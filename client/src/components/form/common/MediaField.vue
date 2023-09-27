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

    <div v-if="file && progress !== null" style="width: 100%; margin-top: 15px">
      <q-linear-progress
        size="10px"
        :value="progress"
        color="positive"
        rounded
      />
      <div>{{ file.name }}</div>

      <q-separator spaced="xl"/>
    </div>

    <!-- Add Media -->
    <q-icon
      v-if="!file"
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
    }
  },

  data () {
    return {
      mediaApi: new MediaApi(),
      progress: null,
      fileChunkSize: 0,
      file: null,
      errorMessage: null
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
      this.errorMessage = null

      const res = await this.checkFile(file)
      const mb = 1024 * 1024
      this.fileChunkSize = mb * res.data.chunk_size

      this.file = file

      if (file.size > this.fileChunkSize) {
        this.progress = 0
        await this.uploadFileByChunks(file, res.data.media_id)
      }
    },

    async uploadFileByChunks (file, mediaId) {
      const chunks = []
      let start = 0
      const end = file.size
      while (start < end) {
        const endOffset = start + this.fileChunkSize
        chunks.push(file.slice(start, endOffset))
        start = endOffset
      }

      const totalChunks = chunks.length

      for (let i = 0; i < totalChunks; i++) {
        if (this.errorMessage) break

        const chunk = chunks[i]
        const data = {
          media_id: mediaId,
          file_chunk: chunk,
          chunk_index: i,
          total_chunks: totalChunks
        }

        await this.uploadChunk(data)
      }
    },

    async uploadChunk (data, attempt = 0) {
      if (attempt >= 3) {
        this.errorMessage = 'От халепа. Спробуйте ще раз.'
        this.file = null
        this.progress = null
        return
      }

      const totalChunks = data.total_chunks
      const currentChunkIndex = data.chunk_index
      const percentagesPerChunk = 100 / totalChunks
      const completedProgress = currentChunkIndex * percentagesPerChunk

      await this.mediaApi.uploadChunk(data, (percentage) => {
        const currentProgress = percentagesPerChunk / 100 * percentage
        this.progress = (completedProgress + currentProgress) / 100
        if (this.progress === 1) {
          setTimeout(() => {
            this.progress = null
          }, 500)
        }
      })
        .catch(async () => {
          await this.uploadChunk(data, ++attempt)
        })
    },

    checkFile (file) {
      return new Promise((resolve, reject) => {
        this.mediaApi.checkFileType(file)
          .then(res => resolve(res))
          .catch(err => {
            this.errorMessage = err.response.data.errors.file_chunk[0]
            reject(err)
          })
      })
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
