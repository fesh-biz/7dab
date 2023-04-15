<template>
  <div class="q-mt-lg profile">
    <!-- Profile Stats -->
    <stats :user-id="me.id"/>

    <q-tabs
      v-model="tab"
      inline-label
      outside-arrows
      mobile-arrows
      no-caps
      class="q-mt-xl text-grey-8"
    >
      <q-tab name="posts" icon="mms" :label="$t('posts')">
        <q-badge color="green" class="q-ml-sm">2</q-badge>
      </q-tab>
      <q-tab name="comments" icon="mode_comment" :label="$t('comments')">
        <q-badge color="green" class="q-ml-sm">2</q-badge>
      </q-tab>
      <q-tab name="answers" icon="forum" :label="$t('answers')">
        <q-badge color="green" class="q-ml-sm">2</q-badge>
      </q-tab>
    </q-tabs>

    <q-card flat bordered>
      <q-card-section>
        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="posts">
            <posts-tabs />
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
  </div>
</template>

<script>
import Stats from 'components/user/Stats'
import PostsTabs from 'components/profile/PostsTabs'
import Me from 'src/models/user/me'

export default {
  name: 'Account',

  components: {
    Stats,
    PostsTabs
  },

  data () {
    return {
      me: Me.query().first(),
      tab: 'posts'
    }
  }
}
</script>

<style lang="sass">
.profile
  .q-tab-panel
    padding: 0
</style>
