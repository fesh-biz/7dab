<template>
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
      <q-badge color="green" class="q-ml-sm">2</q-badge>
    </q-tab>
    <q-tab name="pending" icon="mode_comment" :label="$t('pending')">
      <q-badge color="green" class="q-ml-sm">2</q-badge>
    </q-tab>
    <q-tab name="draft" icon="forum" :label="$t('drafts')">
      <q-badge color="green" class="q-ml-sm">2</q-badge>
    </q-tab>
  </q-tabs>
</template>

<script>
import Me from 'src/models/user/me'

export default {
  name: 'PostsTabs',

  data () {
    return {
      me: Me.query().first(),
      tab: 'approved'
    }
  }
}
</script>
