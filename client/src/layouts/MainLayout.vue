<template>
  <q-layout view="lHh Lpr lFf">
    <q-header class="ml-header text-grey">
      <q-toolbar>
        <q-space/>

        <q-btn-dropdown color="white" class="bg-primary glossy" dusk="ml-menu" no-caps flat dense rounded>
          <div class="row no-wrap q-pa-md">
            <div class="column">
              <!-- General Menu  -->
              <q-list dense>
                <q-item exact clickable :to="{ name: 'home' }">
                  <q-item-section>{{ $t('home_page') }}</q-item-section>
                </q-item>
                <q-item exact clickable :to="{ name: 'createPost' }">
                  <q-item-section>
                    {{ $t('add_post') }}
                  </q-item-section>
                </q-item>
              </q-list>
            </div>

            <q-separator vertical inset class="q-mx-lg"/>

            <div class="column">
              <guest-menu v-if="!me"/>
              <user-menu v-if="me"/>
            </div>
          </div>
        </q-btn-dropdown>
      </q-toolbar>
    </q-header>

    <q-page-container style="padding-top: unset">
      <q-page>
        <router-view/>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import UserMenu from 'components/navbar/UserMenu'
import GuestMenu from 'components/navbar/GuestMenu'
import Me from 'src/models/user/me'

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
.ml-header.fixed-top
  left: unset
  background-color: unset

.ml-menu
  .q-btn__wrapper
    &:before
      background-color: #ffffff
</style>
