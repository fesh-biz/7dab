<template>
  <div class="row justify-center q-pt-xl">
    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-6 col-xl-5">
      <q-card
        class="my-card"
      >
        <q-card-section>
          <div class="text-h6">{{ $t('email_verification') }}</div>
        </q-card-section>

        <q-card-section class="q-pt-none flex justify-center">
          <q-spinner
            color="primary"
            size="3em"
            :thickness="10"
          />
        </q-card-section>

        <q-card-section>
          {{ $t('your_email_verified') }} <br>
          {{ $t('you_ll_be_redirected_in_3_seconds') }}
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<script>
import UserApi from 'src/plugins/api/user'

export default {
  name: 'EmailVerification',

  data () {
    return {
      userApi: new UserApi()
    }
  },

  computed: {
    token () {
      return this.$route.query.t
    }
  },

  created () {
    if (this.token) {
      this.verifyEmail()
    }
  },

  methods: {
    verifyEmail () {
      this.userApi.verifyEmail(this.token)
        .then(() => {
          this.$q.notify({
            message: this.$t('your_email_verified'),
            position: 'center',
            color: 'positive',
            timeout: 3000
          })
        })
    }
  }
}
</script>
