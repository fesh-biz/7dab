<template>
  <div>
    <q-tabs
      v-model="tab"
      inline-label
      outside-arrows
      mobile-arrows
      no-caps
      dense
      class="text-grey-8"
    >
      <q-tab name="approved" icon="mms" :label="$t('published')">
        <q-badge color="green" class="q-ml-sm">{{ totalPosts.approved }}</q-badge>
      </q-tab>
      <q-tab name="pending" icon="mark_chat_unread" :label="$t('pending')">
        <q-badge color="green" class="q-ml-sm">{{ totalPosts.pending }}</q-badge>
      </q-tab>
      <q-tab name="draft" icon="chat_bubble_outline" :label="$t('drafts')">
        <q-badge color="green" class="q-ml-sm">{{ totalPosts.draft }}</q-badge>
      </q-tab>
    </q-tabs>
  </div>
</template>

<script>
import Me from 'src/models/user/me'

export default {
  name: 'PostsTabs',

  props: {
    totalPosts: {
      type: Object,
      required: true
    }
  },

  data () {
    return {
      me: Me.query().first(),
      tab: 'approved'
    }
  }
}
</script>
