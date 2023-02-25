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
      :color="isMyVotePositive === false ? 'red-8' : 'grey-6'"
      size="0.6rem"
      icon="thumb_down"
    />
  </div>
</template>

<script>
import RatingApi from 'src/plugins/api/rating-api'
import MyVote from 'src/models/rating/my-vote'
import RatingModel from 'src/models/rating/rating'
import MeCookies from 'src/plugins/cookies/me'
import Me from 'src/models/user/me'

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
      meCookies: new MeCookies()
    }
  },

  computed: {
    me () {
      return Me.query().first()
    },

    ratingInstance () {
      return RatingModel.query().where(rating => {
        return rating.ratingable_id === this.ratingable.id &&
          rating.ratingable_type_name === this.ratingableTypePlural
      }).first()
    },

    rating () {
      const rating = this.ratingInstance

      const pv = rating?.positive_votes || 0
      const nv = rating?.negative_votes || 0
      const res = pv - nv

      return {
        positiveVotes: pv,
        negativeVotes: nv,
        result: res,
        color: (() => {
          let color = 'light-blue-1'

          if (res > 0) {
            color = 'green-5'
          } else if (res < 0) {
            color = 'red-5'
          }

          return color
        })()
      }
    },

    ratingableTypePlural () {
      return this.ratingable.constructor.name.toLowerCase() + 's'
    },

    isMyVotePositive () {
      if (!this.me) return null

      const vote = MyVote.query().where(vote => {
        return vote.user_id === this.me.id &&
          vote.ratingable_id === this.ratingable.id &&
          vote.ratingable_type_name === this.ratingableTypePlural
      }).first()

      if (!vote) return null

      return vote.is_positive
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

      if ((name === 'up' && this.isMyVotePositive) || (name === 'down' && this.isMyVotePositive === false)) {
        return
      }

      const isIHaveVotedBefore = this.isMyVotePositive !== null
      this.isSubmitting = true
      this.ratingApi.vote(this.ratingableType, this.ratingable.id, name === 'up')
        .then(async res => {
          this.isSubmitting = false

          await MyVote.insertOrUpdate({
            data: res.data
          })

          await this.updateRating(name, isIHaveVotedBefore)

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

    async updateRating (voteType, isIHaveVotedBefore) {
      if (this.ratingInstance) {
        let positiveVotes = this.ratingInstance.positive_votes
        let negativeVotes = this.ratingInstance.negative_votes
        if (voteType === 'up') {
          positiveVotes++
          if (isIHaveVotedBefore) {
            negativeVotes--
          }
        } else {
          negativeVotes++
          if (isIHaveVotedBefore) {
            positiveVotes--
          }
        }

        await RatingModel.update({
          where: this.ratingInstance.id,
          data: {
            positive_votes: positiveVotes,
            negative_votes: negativeVotes
          }
        })
      } else {
        await RatingModel.insert({
          data: {
            ratingable_id: this.ratingable.id,
            ratingable_type_name: this.ratingableTypePlural,
            positive_votes: voteType === 'up' ? 1 : 0,
            negative_votes: voteType === 'down' ? 1 : 0
          }
        })
      }
    }
  }
}
</script>
