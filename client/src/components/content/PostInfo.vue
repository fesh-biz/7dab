<template>
  <div style="color: #757575">
    <!-- Views, Comments, Rating-->
    <div v-if="false" class="flex cursor-default">
      <!-- Rating -->
      <div :dusk="'post-' + post.id + '-info-rating'" class="q-mr-md">
        <post-info-icon icon="thumbs_up_down" :amount="0" :tooltip-label="$t('rating')"/>
      </div>

      <!-- Total Views -->
      <div :dusk="'post-' + post.id + '-info-views'" class="q-mr-md">
        <post-info-icon icon="visibility" :amount="0" :tooltip-label="$t('views')"/>
      </div>

      <!-- Total Comments -->
      <div :dusk="'post-' + post.id + '-info-comments'" class="q-mr-md">
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

export default {
  name: 'PostInfo',

  components: {
    PostInfoIcon,
    Tag
  },

  props: {
    post: {
      type: Object,
      required: true
    }
  }
}
</script>
