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
      const img = this.$refs.editor.getImageScaled()

      const blob = await (await fetch(img.toDataURL())).blob()
      const file = new File([blob], 'avatar.png', { type: blob.type })
      console.log('img', file)
      // this.$refs.image.src = img.toDataURL()
    }
  }
}
</script>
