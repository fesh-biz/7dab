<template>
  <div class="row justify-center q-mt-lg">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
      <stats :user-id="me.id"/>
    </div>
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
