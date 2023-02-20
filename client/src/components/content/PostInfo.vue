<template>
  <div style="color: #757575">
    <!-- Views, Comments, Rating-->
    <div class="flex cursor-default">
      <!-- Rating -->
      <rating
        :ratingable="post"
      />

      <!-- Total Views -->
      <div v-if="false" :dusk="'post-' + post.id + '-info-views'" class="q-mr-md">
        <post-info-icon icon="visibility" :amount="0" :tooltip-label="$t('views')"/>
      </div>

      <!-- Total Comments -->
      <div v-if="false" :dusk="'post-' + post.id + '-info-comments'" class="q-mr-md">
        <post-info-icon icon="question_answer" :amount="0" :tooltip-label="$t('comments')"/>
      </div>
    </div>

    <!-- Tags -->
    <div :dusk="'post-' + post.id + '-info-tags'" class="q-mt-md flex">
      <tag
        v-for="(tag, index) in post.tags"
        :key="'tag' + index"
        :item="tag"
      />
    </div>
  </div>
</template>

<script>
import Tag from 'components/content/Tag'
import PostInfoIcon from 'components/content/PostInfoIcon'
import Rating from 'src/components/rating/Rating'

export default {
  name: 'PostInfo',

  components: {
    PostInfoIcon,
    Tag,
    Rating
  },

  props: {
    post: {
      type: Object,
      required: true
    }
  },

  data () {
    return {
    }
  }
}
</script>

<style lang="sass">
.rating-text
  font-size: 1.4rem
</style>
