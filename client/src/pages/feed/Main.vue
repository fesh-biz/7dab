<template>
  <post-feed url="posts/top" />
</template>

<script>
import PostFeed from 'components/common/PostsFeed'
export default {
  name: 'FeedMain',

  components: { PostFeed },

  data () {
    return {}
  }
}
</script>
