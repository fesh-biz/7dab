<template>
  <div style="color: #757575">
    <!-- Views, Comments, Rating-->
    <div class="flex cursor-default items-center">
      <!-- Rating -->
      <rating
        :ratingable="post"
      />

      <!-- Total Views -->
      <div :dusk="'post-' + post.id + '-info-views'" class="q-mr-md">
        <chip-info
          :label="post.views"
          icon="visibility"
          :tooltip="$t('views')"
        />
      </div>

      <!-- Total Comments -->
      <div :dusk="'post-' + post.id + '-info-comments'" class="q-mr-md">
        <chip-info
          :label="post.comments"
          icon="question_answer"
          :tooltip="$t('comments')"
        />
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
import Rating from 'src/components/rating/Rating'
import ChipInfo from 'components/common/ChipInfo'

export default {
  name: 'PostInfo',

  components: {
    ChipInfo,
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
