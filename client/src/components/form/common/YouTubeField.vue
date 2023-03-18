<template>
  <div ref="field">
    <!-- YouTube Link -->
    <q-input
      dense
      outlined
      v-model="model"
      class="q-mb-md"
      :label="$t('insert_youtube_link')"
    />

    <you-tube v-if="videoId" ref="youtube" :video-id="videoId" />
  </div>
</template>

<script>
import Url from 'src/plugins/tools/url'
import YouTube from 'components/common/YouTube'

export default {
  name: 'YouTubeField',

  components: {
    YouTube
  },

  props: {
    value: {
      type: Object,
      required: true
    }
  },

  data () {
    return {
      url: new Url(),
      videoId: null,
      model: null
    }
  },

  watch: {
    model (val) {
      this.onInput(val)
    }
  },

  created () {
    if (this.value.youtube_id) {
      this.videoId = this.value.youtube_id
      this.model = 'https://www.youtube.com/watch?v=' + this.value.youtube_id
    }
  },

  methods: {
    onInput (val) {
      const videoId = this.url.getUrlQueryVars(val).v || val.split('.be/').pop()

      if (videoId) {
        this.model = 'https://www.youtube.com/watch?v=' + videoId
        this.videoId = videoId
        this.$emit('input', {
          youtube_id: this.videoId
        })
        setTimeout(() => {
          this.$refs.youtube.updateSrc(this.videoId)
        }, 10)
      }
    }
  }
}
</script>
