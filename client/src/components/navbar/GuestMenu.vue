<template>
  <q-list dense>
    <!-- Guest -->
      <q-item dusk="gm-login-link" exact clickable :to="{ name: 'login' }">
        <q-item-section>{{ $t('login') }}</q-item-section>
      </q-item>
      <q-item dusk="gm-registration-link" exact clickable :to="{ name: 'register' }">
        <q-item-section>{{ $t('register') }}</q-item-section>
      </q-item>
  </q-list>
</template>

<script>
import Me from 'src/models/user/Me'

export default {
  name: 'GuestMenu',

  data () {
    return {}
  },

  computed: {
    me () {
      return Me.query().first()
    }
  }
}
</script>
