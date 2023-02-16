<template>
  <div style="color: #757575">
    <!-- Views, Comments, Rating-->
    <div class="flex cursor-default">
      <!-- Rating -->
      <div :dusk="'post-' + post.id + '-info-rating'" class="q-mr-md">
        <!-- Thumb Up -->
        <q-btn round color="green-4" size="sm" icon="thumb_up" />

        <!-- Rating -->
        <q-chip :color="rating.color" text-color="white">
          {{ rating.result }}
          <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
            {{ rating.positiveVotes }} - {{ rating.negativeVotes }}
          </q-tooltip>
        </q-chip>

        <!-- Thumb Down-->
        <q-btn round color="red-4" size="sm" icon="thumb_down" />
      </div>

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
  },

  computed: {
    rating () {
      const pv = this.post.rating.positive_votes
      const nv = this.post.rating.negative_votes
      const res = pv - nv
      return {
        positiveVotes: pv,
        negativeVotes: nv,
        result: res,
        color: (() => {
          let color = 'cyan-1'

          if (res > 0) {
            color = 'green-5'
          } else if (res < 0) {
            color = 'red-5'
          }

          return color
        })()
      }
    }
  },

  created () {
    console.log('this.post', this.post)
  }
}
</script>

<style lang="sass">
.rating-text
  font-size: 1.4rem
</style>
