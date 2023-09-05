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
      mediaApi: new MediaApi()
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

      // if (files.length > 1) {
      //
      // }

      if (files.length === 1) {
        this.mediaApi.upload(files[0])
      }
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
