<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated class="bg-white text-grey">
      <q-toolbar>
        <q-toolbar-title class="flex">
          <q-item :to="{ name: 'home'}">
            <img width="24px" src="~assets/quasar-logo-full.svg" alt="">
          </q-item>
          <q-item>Starter Kit</q-item>
        </q-toolbar-title>

        <div>
          <guest-menu v-if="!me"/>
          <user-menu v-if="me"/>
        </div>
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
