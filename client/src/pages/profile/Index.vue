<template>
  <div class="q-mt-lg profile">
    <!-- Profile Stats -->
    <stats :user-id="me.id" @fetched="fetchContentStats"/>

    <q-linear-progress v-if="isFetchingStats && statsFetched" indeterminate style="position: relative; top: 4px"/>

    <template v-if="!isFetchingStats">
      <q-tabs
        v-model="tab"
        inline-label
        outside-arrows
        mobile-arrows
        no-caps
        class="q-mt-xl text-grey-8"
      >
        <q-tab :disable="!contentStats.posts.total" name="posts" icon="mms" :label="$t('posts')">
          <q-badge color="green" class="q-ml-sm">
            {{ contentStats.posts.total }}
          </q-badge>
        </q-tab>
        <q-tab :disable="!contentStats.total_comments" name="comments" icon="mode_comment" :label="$t('comments')">
          <q-badge color="green" class="q-ml-sm">
            {{ contentStats.total_comments }}
          </q-badge>
        </q-tab>
        <q-tab :disable="!contentStats.total_answers" name="answers" icon="forum" :label="$t('answers')">
          <q-badge color="green" class="q-ml-sm">
            {{ contentStats.total_answers }}
          </q-badge>
        </q-tab>
      </q-tabs>

      <q-card flat bordered>
        <q-card-section>
          <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="posts">
              <posts-tabs :total-posts="contentStats.posts" />
            </q-tab-panel>

            <q-tab-panel name="comments">
              <div class="text-h6">Comments</div>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
            </q-tab-panel>

            <q-tab-panel name="answers">
              <div class="text-h6">Answers</div>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
            </q-tab-panel>
          </q-tab-panels>
        </q-card-section>
      </q-card>
    </template>
  </div>
</template>

<script>
import Stats from 'components/user/Stats'
import PostsTabs from 'components/profile/PostsTabs'
import Me from 'src/models/user/me'
import ProfileApi from 'src/plugins/api/profile'

export default {
  name: 'Account',

  components: {
    Stats,
    PostsTabs
  },

  data () {
    return {
      me: Me.query().first(),
      tab: 'posts',
      isFetchingStats: true,
      api: new ProfileApi(),
      contentStats: null,
      statsFetched: false
    }
  },

  methods: {
    async fetchContentStats () {
      this.statsFetched = true
      const res = await this.api.fetchContentStats()
      this.isFetchingStats = false
      this.contentStats = res.data
    }
  }
}
</script>

<style lang="sass">
.profile
  .q-tab-panel
    padding: 0
</style>
