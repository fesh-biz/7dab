<template>
  <q-layout view="lHh Lpr lFf">
    <q-header class="ml-header text-grey">
      <q-toolbar>
        <q-space/>

        <q-btn-dropdown
          color="white"
          id="menuButton"
          class="bg-primary glossy"
          dusk="ml-menu"
          dropdown-icon="menu"
          no-caps
          flat
          dense
          rounded
        >
          <div style="width: 350px">
            <!-- Title -->
            <div class="q-pa-md bg-light-blue-1" style="border-radius: 5px 5px 0 0">
              <strong>Теревеньки - </strong> Українці зі всього світу спілкуються тут!
              Приєднуйся!
            </div>

            <!-- Menu -->
            <div class="row no-wrap q-pa-md">
              <div class="column">
                <!-- General Menu  -->
                <q-list dense>
                  <!-- Home Page -->
                  <q-item tag="a" href="/" native>
                    <q-item-section>{{ $t('home_page') }}</q-item-section>
                  </q-item>

                  <!-- Search Page -->
                  <q-item exact clickable :to="{ name: 'search' }">
                    <q-item-section>
                      {{ $t('search') }}
                    </q-item-section>
                  </q-item>

                  <!-- Add Post -->
                  <q-item exact clickable :to="{ name: 'createPost' }">
                    <q-item-section>
                      {{ $t('add_post') }}
                    </q-item-section>
                  </q-item>
                </q-list>
              </div>

              <q-separator vertical inset class="q-mx-lg"/>

              <!-- User Menu -->
              <div class="column">
                <guest-menu v-if="!me"/>
                <user-menu v-if="me"/>
              </div>
            </div>
          </div>
        </q-btn-dropdown>
      </q-toolbar>
    </q-header>

    <q-page-container style="padding-top: unset">
      <q-page>
        <div class="row justify-center q-px-sm">
          <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
            <router-view/>
          </div>
        </div>
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
  },

  mounted () {
    this.highlightMenuButton()
  },

  methods: {
    highlightMenuButton () {
      const button = document.querySelector('#menuButton')

      setTimeout(() => {
        button.style.transform = 'scale(6)'
        setTimeout(() => {
          button.style.transform = 'scale(1)'
          setTimeout(() => {
            button.style.transform = 'scale(6)'
            setTimeout(() => {
              button.style.transform = 'scale(1)'
            }, 300)
          }, 300)
        }, 300)
      }, 300)
    }
  }
}
</script>

<style lang="sass">
#menuButton
  transition: all 0.3s ease-in-out

.ml-header.fixed-top
  left: unset
  background-color: unset

.ml-menu
  .q-btn__wrapper
    &:before
      background-color: #ffffff
</style>
