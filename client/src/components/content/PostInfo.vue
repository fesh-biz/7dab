<template>
  <div style="color: #757575">
    <!-- Views, Comments, Rating-->
    <div class="flex cursor-default items-center">
      <!-- Rating -->
      <rating
        :ratingable="post"
        ratingable-type="post"
      />

      <!-- Total Views -->
      <div :dusk="'post-' + post.id + '-info-views'">
        <chip-info
          :label="post.views"
          icon="visibility"
          :tooltip="$t('views')"
        />
      </div>

      <!-- Total Comments -->
      <div :dusk="'post-' + post.id + '-info-comments'">
        <chip-info
          :label="post.comments"
          icon="question_answer"
          :tooltip="$t('total_comments')"
        >
          <q-item v-if="!isPostView" style="padding: 5px; margin-left: 5px" dense :to="{name: 'postView', params: {id: post.id, toComments: true}}">
            <q-item-section>
              <q-icon color="grey-7" name="launch" size="sm"/>
            </q-item-section>
          </q-item>
        </chip-info>
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
    },
    isPostView: {
      type: Boolean,
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
