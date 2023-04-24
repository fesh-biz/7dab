<template>
  <q-dialog v-model="isOpen">
    <q-card>
      <!-- Editor -->
      <q-card-section>
        <vue-avatar
          :width="300"
          :height="300"
          :border-radius="150"
          :border="0"
          :scale="scale"
          ref="editor"
        />

        <q-slider v-model="scale" :min="1" :max="3" :step="0.02"/>
      </q-card-section>

      <!-- Actions -->
      <q-card-actions align="right">
        <!-- Cancel -->
        <q-btn
          no-caps
          :disable="isUploading"
          :label="$t('cancel')"
          v-close-popup
        />

        <!-- Save -->
        <q-btn
          no-caps
          :disable="isUploading"
          :loading="isUploading"
          :label="$t('save')"
          color="primary"
          @click="upload"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import { VueAvatar } from 'vue-avatar-editor-improved'
import Profile from 'src/plugins/api/profile'
import Me from 'src/models/user/me'

export default {
  name: 'AvatarEditor',

  components: { VueAvatar },

  props: {
    value: {
      type: Boolean,
      required: true
    }
  },

  data () {
    return {
      scale: 1,
      isUploading: false
    }
  },

  computed: {
    isOpen: {
      get () {
        return this.value
      },
      set (val) {
        this.$emit('input', val)
      }
    }
  },

  methods: {
    async upload () {
      this.isUploading = true
      const img = this.$refs.editor.getImageScaled()

      const blob = await (await fetch(img.toDataURL())).blob()
      const file = new File([blob], 'avatar.png', { type: blob.type })

      this.convertPngToJpeg(file)
        .then(res => {
          this.api.uploadAvatar(res)
            .then(() => {
              const me = Me.query().first()

              Me.update({
                where: me.id,
                data: {
                  has_avatar: true
                }
              })

              this.isUploading = false
              this.isOpen = false
            })
        })
    },

    convertPngToJpeg (pngFile) {
      return new Promise((resolve, reject) => {
        const canvas = document.createElement('canvas')
        const ctx = canvas.getContext('2d')

        const img = new Image()
        img.onload = function () {
          canvas.width = img.width
          canvas.height = img.height
          ctx.drawImage(img, 0, 0)

          canvas.toBlob(blob => {
            const jpegFile = new File([blob], pngFile.name.replace('.png', '.jpg'), { type: 'image/jpeg' })
            resolve(jpegFile)
          }, 'image/jpeg', 0.9)
        }
        img.onerror = reject
        img.src = URL.createObjectURL(pngFile)
      })
    }
  }
}
</script>
