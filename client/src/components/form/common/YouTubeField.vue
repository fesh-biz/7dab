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

    <q-linear-progress v-if="isFetching" class="q-my-lg" indeterminate/>

    <you-tube v-if="videoId && !isFetching" ref="youtube" :video-id="videoId" />
  </div>
</template>

<script>
import Url from 'src/plugins/tools/url'
import YouTube from 'components/common/YouTube'
import axios from 'axios'

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
      model: null,
      isFetching: false
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
    async onInput () {
      try {
        this.videoId = null
        this.isFetching = true
        const response = await axios.get(`https://www.youtube.com/oembed?url=${this.model}&format=json`)
        const videoId = response.data.html.match(/embed\/(.*)\?/)[1]
        this.isFetching = false

        this.model = 'https://www.youtube.com/watch?v=' + videoId
        this.videoId = videoId
        this.$emit('input', {
          youtube_id: this.videoId
        })
        setTimeout(() => {
          this.$refs.youtube.updateSrc(this.videoId)
        }, 10)
      } catch (e) {
        this.isFetching = false

        this.$q.notify({
          color: 'negative',
          message: 'Invalid YouTube video URL'
        })
      }
    }
  }
}
</script>
