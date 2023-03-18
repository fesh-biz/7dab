<template>
  <div ref="field">
    <!-- YouTube Link -->
    <q-input
      dense
      outlined
      v-model="model"
      class="q-mb-md"
      :label="$t('insert_youtube_link')"
      :error="isError"
      :error-message="$t('video_for_given_youtube_link_was_not_found')"
    />

    <!--<q-linear-progress class="q-mt-lg" v-if="isFetching" indeterminate/>-->

    <iframe
      frameborder="0"
      allowfullscreen="1"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
      title="Lindsey Stirling - O Holy Night (Official Music Video)"
      width="100%"
      height="400"
      src="https://www.youtube.com/embed/n3YBq0QWmbU" />
      <!--src="https://www.youtube.com/embed/n3YBq0QWmbU?autoplay=0&amp;time=0&amp;enablejsapi=1&amp;origin=http%3A%2F%2Flocalhost%3A8080&amp;widgetid=2" />-->
  </div>
</template>

<script>
import Url from 'src/plugins/tools/url'

export default {
  name: 'YouTubeField',

  props: {
    value: {
      type: Object,
      required: true
    }
  },

  data () {
    return {
      url: new Url(),
      isFetching: false,
      isError: false,
      videoId: null,
      reloading: 0,
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
      const videoId = this.url.getUrlQueryVars(val).v || null
      const model = 'https://www.youtube.com/watch?v=' + videoId

      if (this.model !== model) {
        this.model = model
      }

      if (videoId) {
        this.reloading++
        setTimeout(() => {
          this.videoId = videoId
          this.isFetching = true
          this.isError = false
        }, 10)
      }
    },

    onReady (e) {
      const title = e.target.videoTitle
      this.isError = !title
      this.isFetching = false
      console.log('onReady')

      if (!this.isError) {
        this.$emit('input', {
          youtube_id: this.videoId,
          title: title
        })
      }

      if (this.isError) {
        this.videoId = null
        this.reloading++
      }
    }
  }
}
</script>
