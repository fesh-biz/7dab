<template>
  <div style="color: #757575">
    <q-linear-progress v-if="isSubmitting" indeterminate/>

    <!-- Views, Comments, Rating-->
    <div class="flex cursor-default">
      <!-- Rating -->
      <div :dusk="'post-' + post.id + '-info-rating'" class="q-mr-md">
        <!-- Thumb Up -->
        <q-btn @click="vote('up')" round color="green-4" size="sm" icon="thumb_up"/>

        <!-- Rating -->
        <q-chip :color="rating.color" :text-color="rating.result === 0 ? 'black' : 'white'">
          {{ rating.result }}
          <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
            {{ rating.positiveVotes }} - {{ rating.negativeVotes }}
          </q-tooltip>
        </q-chip>

        <!-- Thumb Down-->
        <q-btn @click="vote('down')" round color="red-4" size="sm" icon="thumb_down"/>
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
import RatingApi from 'src/plugins/api/rating-api'

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

  data () {
    return {
      isSubmitting: false,
      ratingApi: new RatingApi()
    }
  },

  computed: {
    rating () {
      const pv = this.post?.rating?.positive_votes || 0
      const nv = this.post?.rating?.negative_votes || 0
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

  methods: {
    vote (name) {
      this.isSubmitting = true
      this.ratingApi.vote('post', this.post.id, name === 'up')
        .then(res => {
          this.isSubmitting = false
        })
        .catch(err => {
          this.isSubmitting = false
          this.$q.notify({
            message: err.response.data.message,
            position: 'center',
            color: 'red'
          })
        })
    }
  }
}
</script>

<style lang="sass">
.rating-text
  font-size: 1.4rem
</style>
