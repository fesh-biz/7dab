<template>
  <div class="column items-center">
    <user-avatar :src="me.avatar"/>

    <div dusk="um-user-name" class="text-subtitle1 q-mt-md q-mb-xs">{{ me.login }}</div>

    <q-btn
        dusk="um-logout-link"
        color="primary"
        :label="$t('logout')"
        @click="logout"
        push
        size="sm"
        v-close-popup
    />
  </div>
</template>

<script>
import Me from 'src/models/user/me'
import MeCookies from 'src/plugins/cookies/me'
import UserAvatar from 'components/common/UserAvatar'
import UserApi from 'src/plugins/api/user'
import Token from 'src/plugins/cookies/token'
import Api from 'src/plugins/api/api'

export default {
  name: 'UserMenu',

  components: {
    UserAvatar
  },

  data () {
    return {
      userApi: new UserApi(),
      tokenCookies: new Token(),
      meCookies: new MeCookies(),
      api: new Api()
    }
  },

  computed: {
    me () {
      return Me.query().first()
    }
  },

  methods: {
    async logout () {
      console.log('logout')
      await this.userApi.logout()
        .then(() => {
          this.deleteUserData()
        })
        .catch(() => {
          this.deleteUserData()
        })
    },

    deleteUserData () {
      console.log('deleting user data')
      this.api.deleteBearer()
      this.tokenCookies.delete()
      this.meCookies.delete()
      Me.deleteAll()
      if (this.$route.name !== 'home') {
        this.$router.push({ name: 'home' })
      }
    }
  }
}
</script>
