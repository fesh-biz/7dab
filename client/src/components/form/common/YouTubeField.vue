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

    <q-linear-progress class="q-mt-lg" v-if="isFetching" indeterminate/>

    <youtube
      :style="{display: !isFetching && !isError ? 'block' : 'none'}"
      v-if="videoId"
      :key="reloading"
      :video-id="videoId"
      player-width="100%"
      :player-height="$q.platform.is.mobile ? 200 : 400"
      @ready="onReady"
    />

    <!-- https://www.youtube.com/watch?v=N3yDf8lkGEY -->
    <!--  https://www.youtube.com/watch?v=n3YBq0QWmbU -->
    <!--  https://www.youtube.com/watch?v=n3YBq0QWmb // error -->
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
      this.videoId = this.url.getUrlQueryVars(val).v || null

      if (this.videoId) {
        this.isFetching = true
        this.isError = false
      }
    },

    onReady (e) {
      const title = e.target.videoTitle
      this.isError = !title
      this.isFetching = false

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
