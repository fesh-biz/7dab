<template>
  <div class="row justify-center">
    <div class="col-sm-12 col-xs-12 col-md-6 col-lg-4 col-xl-4">
      <div class="flex column justify-center q-pa-md" style="height: 100vh">
        <q-form
            dusk="fp-forgot-password-form"
            @submit="onSubmit"
            class="q-gutter-md"
        >
          <!-- Title -->
          <q-card>
            <q-card-section>
              <div class="text-h6">{{ $t('reset_password') }}</div>
            </q-card-section>

            <q-separator/>

            <!-- email -->
            <q-card-section>
              <q-input
                  dusk="fp-email-input"
                  outlined
                  dense
                  class="q-mb-md"
                  v-model="form.email"
                  :label="$t('email')"
                  :error="!!validator.errors.email"
                  :error-message="validator.errors.email"
                  @input="validator.resetErrors()"
                  :hint="$t('forgot_your_password_hint')"
              />

              <q-banner v-if="validator.errors.errorMessage" rounded inline-actions class="text-white bg-red">
                {{ validator.errors.errorMessage }}
              </q-banner>

              <q-banner
                  dusk="fp-info-message"
                  v-if="infoMessage"
                  rounded
                  inline-actions
                  class="text-white bg-positive q-mt-lg"
              >
                {{ infoMessage }}
              </q-banner>
            </q-card-section>

            <q-separator/>

            <!-- Buttons -->
            <q-card-actions class="q-pa-md">
              <q-btn
                  dusk="fp-submit-button"
                  :loading="formIsBusy"
                  :label="$t('email_password_reset_link')"
                  type="submit"
                  color="primary"
              />
            </q-card-actions>
          </q-card>
        </q-form>
      </div>
    </div>
  </div>
</template>

<script>
import Validator from '../../plugins/Validator'
import UserApi from 'src/plugins/api/user'

const formModel = {
  email: ''
}

export default {
  name: 'ForgotPassword',

  data () {
    return {
      form: formModel,
      formIsBusy: false,
      validator: new Validator(formModel),
      infoMessage: '',
      userApi: new UserApi()
    }
  },

  methods: {
    onSubmit () {
      this.formIsBusy = true
      this.userApi.forgotPassword(this.form.email)
        .then((res) => {
          this.formIsBusy = false
          this.infoMessage = res.data.message
        })
        .catch((err) => {
          this.formIsBusy = false
          this.validator.setErrors(err)
        })
    }
  }
}
</script>
