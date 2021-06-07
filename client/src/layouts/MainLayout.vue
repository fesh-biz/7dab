<template>
  <q-layout view="lHh Lpr lFf">
    <q-header style="background-color: unset" class="text-grey">
      <q-toolbar>
        <q-toolbar-title class="flex">
          <q-item :to="{ name: 'home'}">
            <img width="24px" src="~assets/quasar-logo-full.svg" alt="">
          </q-item>
          <q-item>7dab</q-item>
        </q-toolbar-title>

        <q-btn-dropdown class="ml-menu" dusk="ml-menu" no-caps flat dense rounded icon="account_circle">
          <div class="row no-wrap q-pa-md">
            <div class="column items-center">
              <guest-menu v-if="!me"/>
              <user-menu v-if="me"/>
            </div>
          </div>
        </q-btn-dropdown>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <q-page padding>
        <router-view/>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import UserMenu from 'components/navbar/UserMenu'
import GuestMenu from 'components/navbar/GuestMenu'
import Me from 'src/models/user/Me'

export default {
  name: 'MainLayout',
  components: {
    GuestMenu,
    UserMenu
  },
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

<style lang="sass">
.ml-menu
  .q-btn__wrapper
    &:before
      background-color: #ffffff
</style>
