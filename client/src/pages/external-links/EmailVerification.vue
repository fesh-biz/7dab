<template>
  <div class="flex column justify-center q-pa-md" style="height: 100vh">
    <q-card
      class="my-card"
    >
      <q-card-section>
        <div class="text-h6">{{ $t('email_verification') }}</div>
      </q-card-section>

      <q-card-section v-if="isSubmitting" class="q-pt-none flex justify-center">
        <q-linear-progress indeterminate/>
      </q-card-section>

      <q-card-section v-if="isVerified">
        {{ $t('your_email_verified') }} <br>
        {{ $t('thanks') }}
      </q-card-section>

      <q-card-section v-if="!isSubmitting">
        <q-item class="bg-light-blue-1" :to="{name: 'home'}">
          <q-item-section class="text-center">
            {{ $t('home_page') }}
          </q-item-section>
        </q-item>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import UserApi from 'src/plugins/api/user'

export default {
  name: 'EmailVerification',

  data () {
    return {
      userApi: new UserApi(),
      isSubmitting: false,
      isVerified: false
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
      this.isSubmitting = true
      this.userApi.verifyEmail(this.token)
        .then((res) => {
          this.isSubmitting = false
          this.isVerified = true
        })
        .catch(() => {
          this.isSubmitting = false

          this.$q.notify({
            message: this.$t('something_went_wrong') + ' Можливо ваш email вже підтверджено.',
            color: 'negative',
            position: 'center'
          })
        })
    }
  }
}
</script>
