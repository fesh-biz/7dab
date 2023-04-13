<template>
  <q-card flat bordered>
    <q-card-section v-if="isFetching">
      <q-linear-progress  indeterminate/>
    </q-card-section>

    <template v-if="!isFetching">
      <!-- Login, With Us From-->
      <q-item>
        <q-item-section avatar>
          <q-avatar>
            <user-avatar size="40px" />
          </q-avatar>
        </q-item-section>

        <q-item-section>
          <q-item-label style="font-size: 1.2rem; font-weight: bold; color: #585961">{{ stats.profile.login }}</q-item-label>
          <q-item-label caption>З нами: {{ moment(stats.profile.with_us_since).fromNow(true) }}</q-item-label>
        </q-item-section>
      </q-item>

      <q-separator />

      <q-card-section>
        <!-- Rating -->
        <chip-info
          icon="thumbs_up_down"
          :label="stats.rating.positive - stats.rating.negative"
          :tooltip="$t('rating') + ` (${stats.rating.positive} - ${stats.rating.negative})`"
        />

        <!-- Total User's Positive Votes -->
        <chip-info
          bg-color="green-3"
          icon="thumb_up_off_alt"
          :label="stats.votes.positive"
          :tooltip="$t('voted_positive')"
        />

        <!-- Total User's Negative Votes -->
        <chip-info
          bg-color="red-3"
          icon="thumb_down_off_alt"
          :label="stats.votes.negative"
          :tooltip="$t('voted_negative')"
        />

        <!-- Total Posts -->
        <chip-info
          icon="mms"
          :label="stats.total_posts"
          :tooltip="$t('total_posts')"
        />

        <!-- Total Comments -->
        <chip-info
          icon="mode_comment"
          :label="stats.total_comments"
          :tooltip="$t('total_comments')"
        />
      </q-card-section>
    </template>
  </q-card>
</template>

<script>
import UserAvatar from 'components/common/UserAvatar'
import UserApi from 'src/plugins/api/user'
import moment from 'moment'
import ChipInfo from 'components/common/ChipInfo'
moment.locale('uk')

export default {
  name: 'Stats',

  props: {
    userId: {
      type: Number,
      required: true
    }
  },

  components: {
    ChipInfo,
    UserAvatar
  },

  data () {
    return {
      stats: {},
      isFetching: true,
      api: new UserApi(),
      moment: moment
    }
  },

  created () {
    this.fetchStats()
  },

  methods: {
    async fetchStats () {
      const res = await this.api.fetchStats(this.userId)
      this.stats = res.data
      this.isFetching = false
    }
  }
}
</script>
