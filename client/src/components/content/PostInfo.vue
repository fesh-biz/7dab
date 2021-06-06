<template>
  <div style="color: #757575">
    <!-- Views, Comments, Rating-->
    <div class="flex">
      <!-- Rating -->
      <div class="q-mr-md">
        <post-info-icon icon="thumbs_up_down" :amount="rating" :tooltip-label="$t('rating')"/>
      </div>

      <!-- Total Views -->
      <div class="q-mr-md">
        <post-info-icon icon="visibility" :amount="totalViews" :tooltip-label="$t('views')"/>
      </div>

      <!-- Total Comments -->
      <div class="q-mr-md">
        <post-info-icon icon="question_answer" :amount="totalComments" :tooltip-label="$t('comments')"/>
      </div>
    </div>

    <!-- Tags -->
    <div class="q-mt-md flex">
      <tag
        v-for="(tag, index) in tags"
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
    tags: {
      type: Array,
      required: true
    },
    rating: {
      type: Number,
      required: true
    },
    totalViews: {
      type: Number,
      required: true
    },
    totalComments: {
      type: Number,
      required: true
    }
  }
}
</script>
