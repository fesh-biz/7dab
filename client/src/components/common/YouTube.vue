<template>
  <iframe
    frameborder="0"
    allowfullscreen="1"
    width="100%"
    :height="$q.platform.is.mobile ? 200 : 400"
    :src="src"
  />
</template>

<script>
export default {
  name: 'YouTube',

  props: {
    videoId: {
      type: String,
      required: true
    }
  },

  data () {
    return {
      src: null
    }
  },

  created () {
    this.src = 'https://www.youtube.com/embed/' + this.videoId
  },

  methods: {
    updateSrc (videoId) {
      this.src = 'https://www.youtube.com/embed/' + videoId
    }
  }
}
</script>
