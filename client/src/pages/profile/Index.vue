<template>
  <div class="q-mt-lg">
    <!-- Profile Stats -->
    <stats :user-id="me.id"/>

    <!-- Profile Tabs -->
    <q-tabs
      v-model="tab"
      inline-label
      outside-arrows
      mobile-arrows
      no-caps
      class="q-mt-lg text-grey-8"
    >
      <q-tab name="posts" icon="mms" :label="$t('posts')" />
      <q-tab name="comments" icon="mode_comment" :label="$t('comments')" />
    </q-tabs>

    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="posts">
        <div class="text-h6">Posts</div>
        Lorem ipsum dolor sit amet consectetur adipisicing elit.
      </q-tab-panel>

      <q-tab-panel name="comments">
        <div class="text-h6">Comments</div>
        Lorem ipsum dolor sit amet consectetur adipisicing elit.
      </q-tab-panel>
    </q-tab-panels>
  </div>
</template>

<script>
import Stats from 'components/profile/Stats'
import Me from 'src/models/user/me'

export default {
  name: 'Account',

  components: {
    Stats
  },

  data () {
    return {
      me: Me.query().first(),
      tab: 'posts'
    }
  }
}
</script>
