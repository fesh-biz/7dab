<template>
  <div class="row justify-center">
    <div class="col-sm-12 col-xs-12 col-md-6 col-lg-4 col-xl-4">
      <div class="flex column justify-center q-px-md" style="height: 100vh">
        <q-form
            dusk="l-login-form"
            @submit="onSubmit"
        >
          <!-- Title -->
          <q-card>
            <q-card-section>
              <div class="text-h6">{{ $t('login') }}</div>
            </q-card-section>

            <q-separator/>

            <!-- login, password -->
            <q-card-section>
              <q-input
                  dusk="l-email-input"
                  outlined
                  dense
                  autocomplete="username"
                  v-model="form.email"
                  :label="$t('email')"
                  :error="!!validator.errors.email"
                  :error-message="validator.errors.email"
                  @input="validator.resetErrors()"
              />

              <q-input
                  dusk="l-password-input"
                  outlined
                  dense
                  autocomplete="current-password"
                  type="password"
                  v-model="form.password"
                  :label="$t('password')"
                  :error="!!validator.errors.password"
                  :error-message="validator.errors.password"
                  @input="validator.resetErrors()"
              />

              <q-banner
                  dusk="l-error-message"
                  v-if="validator.errors.error_message"
                  rounded
                  inline-actions
                  class="text-white bg-red q-mb-md"
              >
                {{ validator.errors.error_message }}
              </q-banner>

              <q-item dusk="l-forgot-password-link" :to="{ name: 'forgot_password' }" clickable="">
                <q-item-section>{{ $t('forgot_your_password') }}?</q-item-section>
              </q-item>
            </q-card-section>

            <q-separator/>

            <!-- Buttons -->
            <q-card-actions class="q-pa-md">
              <q-btn dusk="l-login-form-submit" :disable="formIsBusy" :loading="formIsBusy" :label="$t('log_in')"
                     type="submit" color="primary"/>
            </q-card-actions>
          </q-card>
        </q-form>
      </div>
    </div>
  </div>
</template>

<script>
import Validator from '../../plugins/Validator'
import { api } from 'boot/axios'
import TokenApi from 'src/plugins/api/token'
import UserApi from 'src/plugins/api/user'
import TokenCookies from 'src/plugins/cookies/tokenCookies'
import Me from 'src/models/user/Me'

const formModel = {
  email: null,
  password: null
}

export default {
  name: 'Login',
  data () {
    return {
      form: formModel,
      formIsBusy: false,
      validator: new Validator(formModel),
      tokenApi: new TokenApi(),
      userApi: new UserApi(),
      tokenCookies: new TokenCookies()
    }
  },

  created () {
    delete api.defaults.headers.common.Authorization
  },

  methods: {
    onSubmit () {
      this.formIsBusy = true
      this.tokenApi.createToken(this.form.email, this.form.password)
        .then((res) => {
          this.formIsBusy = false

          this.tokenCookies.set(res.data)
          api.defaults.headers.common.Authorization = this.tokenCookies.getAuthorizationToken()

          this.userApi.fetchMe()
            .then(res => {
              Me.create({
                data: res.data
              })
              this.$router.push({ name: 'home' })
            })
        })
        .catch((err) => {
          this.formIsBusy = false
          this.validator.setErrors(err)
        })
    }
  }
}
</script>
