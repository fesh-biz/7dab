<template>
  <you-tube
    :video-id="data.youtube_id"
  />
</template>

<script>
import YouTube from 'components/common/YouTube'
import PostYouTube from 'src/models/content/post-you-tube'

export default {
  name: 'PostYoutube',

  components: {
    YouTube
  },

  props: {
    data: {
      type: PostYouTube,
      required: true
    }
  },

  data () {
    return {}
  }
}
</script>
