<template>
  <q-btn-dropdown dusk="gm-guest-menu" no-caps flat dense rounded icon="account_circle">
    <div class="row no-wrap q-pa-md">
      <div class="column">
        <q-list dense>
          <!-- Guest -->
          <template>
            <q-item dusk="gm-login-link" exact clickable :to="{ name: 'login' }">
              <q-item-section>{{ $t('login') }}</q-item-section>
            </q-item>
            <q-item dusk="gm-registration-link" exact clickable :to="{ name: 'register' }">
              <q-item-section>{{ $t('register') }}</q-item-section>
            </q-item>
          </template>
        </q-list>
      </div>
    </div>
  </q-btn-dropdown>
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
