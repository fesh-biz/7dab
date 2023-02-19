<template>
  <div
    :dusk="`${ratingableType}-${ratingable.id}-rating`"
    class="q-mr-md"
  >
    <q-linear-progress
      v-if="isSubmitting"
      indeterminate
      style="margin-top: -4px"
    />

    <!-- Thumb Up -->
    <q-btn
      @click="vote('up')"
      round
      size="0.6rem"
      :color="isMyVotePositive ? 'green-8' : 'grey-6'"
      icon="thumb_up"
    />

    <!-- Rating -->
    <q-chip :color="rating.color" :text-color="rating.result === 0 ? 'black' : 'white'">
      {{ rating.result }}
      <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
        {{ rating.positiveVotes }} - {{ rating.negativeVotes }}
      </q-tooltip>
    </q-chip>

    <!-- Thumb Down-->
    <q-btn
      @click="vote('down')"
      round
      :color="isMyVoteNegative ? 'red-8' : 'grey-6'"
      size="0.6rem"
      icon="thumb_down"
    />
  </div>
</template>

<script>
import RatingApi from 'src/plugins/api/rating-api'
import MyVote from 'src/models/rating/my-vote'
import RatingModel from 'src/models/rating/rating'
import Me from 'src/plugins/cookies/me'

export default {
  name: 'Rating',

  props: {
    ratingable: {
      type: Object,
      required: true
    },
    ratingableType: {
      type: String,
      required: true
    }
  },

  data () {
    return {
      isSubmitting: false,
      ratingApi: new RatingApi(),
      meCookies: new Me()
    }
  },

  computed: {
    rating () {
      const pv = this.ratingable?.rating?.positive_votes || 0
      const nv = this.ratingable?.rating?.negative_votes || 0
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
    },

    isMyVotePositive () {
      if (!this.ratingable.my_vote) {
        return false
      }

      return this.ratingable.my_vote.is_positive
    },

    isMyVoteNegative () {
      if (!this.ratingable.my_vote) {
        return false
      }

      return !this.ratingable.my_vote.is_positive
    }
  },

  methods: {
    vote (name) {
      if (!this.meCookies.get()) {
        this.$q.notify({
          message: this.$t('need_to_login_or_register'),
          position: 'center',
          color: 'negative'
        })

        return
      }

      if ((name === 'up' && this.isMyVotePositive) || (name === 'down' && this.isMyVoteNegative)) {
        return
      }

      this.isSubmitting = true
      this.ratingApi.vote('post', this.ratingable.id, name === 'up')
        .then(res => {
          this.isSubmitting = false

          MyVote.insertOrUpdate({
            data: res.data
          })

          this.updateRating(name === 'up')

          this.$q.notify({
            message: this.$t('your_vote_is_accepted'),
            position: 'center',
            color: 'green'
          })
        })
        .catch(err => {
          this.isSubmitting = false
          this.$q.notify({
            message: err.response.data.message,
            position: 'center',
            color: 'red'
          })
        })
    },

    updateRating (isUpVote) {
      if (this.ratingable.rating && this.ratingable.my_vote) {
        let positiveVotes = this.ratingable.rating.positive_votes
        let negativeVotes = this.ratingable.rating.negative_votes
        if (isUpVote) {
          positiveVotes++
          negativeVotes--
        } else {
          positiveVotes--
          negativeVotes++
        }

        RatingModel.update({
          where: this.ratingable.rating.id,
          data: {
            positive_votes: positiveVotes,
            negative_votes: negativeVotes
          }
        })
      } else {
        RatingModel.insert({
          data: {
            ratingable_id: this.ratingable.id,
            ratingable_type_name: 'posts',
            positive_votes: isUpVote ? 1 : 0,
            negative_votes: isUpVote ? 0 : 1
          }
        })
      }
    }
  }
}
</script>
