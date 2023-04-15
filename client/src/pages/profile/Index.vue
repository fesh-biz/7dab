<template>
  <div class="q-mt-lg">
      <stats :user-id="me.id"/>
  </div>
</template>

<script>
import Stats from 'components/profile/Stats'
import Me from 'src/models/user/me'

export default {
  name: 'Account',

  components: {
    Stats
  },

  data () {
    return {
      me: Me.query().first()
    }
  }
}
</script>
