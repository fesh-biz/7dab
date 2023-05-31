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
    getYouTubeId (url) {
      let videoId = null
      if (url.includes('youtu.be')) {
        videoId = url.split('/').pop()
      }

      if (url.includes('v=')) {
        const regex = /[?&]v=([^&#]+)/
        const match = regex.exec(url)
        const v = match && decodeURIComponent(match[1].replace(/\+/g, ' '))

        videoId = v || null
      }

      if (url.includes('shorts')) {
        videoId = url.split('/').pop()
        if (videoId.includes('?')) {
          videoId = videoId.split('?')[0]
        }
      }

      return videoId
    },

    onInput () {
      this.videoId = null
      this.isFetching = true

      const videoId = this.getYouTubeId(this.model)
      this.isFetching = false

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
</script>
