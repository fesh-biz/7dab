<template>
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

              <!-- Admin Menu -->
              <admin-menu v-if="me && me.id === 1"/>
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
</template>

<script>
import Me from 'src/models/user/me'
import GuestMenu from 'components/navbar/GuestMenu'
import UserMenu from 'components/navbar/UserMenu'
import AdminMenu from 'components/navbar/AdminMenu'

export default {
  name: 'NavHeader',

  components: { AdminMenu, GuestMenu, UserMenu },

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
