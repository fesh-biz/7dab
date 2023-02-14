<template>
  <div class="row justify-center">
    <div class="col-sm-12 col-xs-12 col-md-6 col-lg-4 col-xl-4">
      <div class="flex column justify-center q-pa-md" style="height: 100vh">
        <q-card>
          <!-- Form and Button -->
          <template v-if="!isDisplayEmailVerificationMessage">
            <!-- Title -->
            <q-card-section>
              <p class="text-h5">{{ $t('registration') }}</p>
            </q-card-section>
            <q-separator/>

            <!-- Form -->
            <q-card-section>
              <q-form dusk="r-registration-form">
                <!-- name -->
                <q-input
                  outlined=""
                  dense=""
                  dusk="r-name-input"
                  v-model="form.login"
                  :label="$t('user_login')"

                  :error="!!validator.errors.login"
                  :error-message="validator.errors.login"

                  @input="validator.resetFieldError('login')"
                />

                <!-- email -->
                <q-input
                  dusk="r-email-input"
                  outlined=""
                  dense=""
                  autocomplete="username"
                  v-model="form.email"
                  :label="$t('email')"

                  :error="!!validator.errors.email"
                  :error-message="validator.errors.email"

                  @input="validator.resetFieldError('email')"
                />

                <!-- password -->
                <q-input
                  dusk="r-password-input"
                  outlined=""
                  dense=""
                  type="password"
                  autocomplete="new-password"
                  v-model="form.password"
                  :label="$t('password')"

                  :error="!!validator.errors.password"
                  :error-message="validator.errors.password"

                  @input="validator.resetFieldError('password')"
                />

                <!-- password_confirmation -->
                <q-input
                  dusk="r-password-confirmation-input"
                  outlined=""
                  dense=""
                  type="password"
                  autocomplete="new-password"
                  v-model="form.password_confirmation"
                  :label="$t('password_confirmation')"

                  :error="!!validator.errors.password_confirmation"
                  :error-message="validator.errors.password_confirmation"

                  @input="validator.resetFieldError('password_confirmation')"
                />
              </q-form>
            </q-card-section>

            <q-separator/>

            <!-- Buttons -->
            <q-card-section
            >
              <q-btn
                dusk="r-registration-button"
                color="primary"
                :loading="isSubmitting"
                :disable="isSubmitting"
                :label="$t('register')"
                @click="submit"
              />
            </q-card-section>
          </template>

          <q-card-section v-if="isDisplayEmailVerificationMessage" class="bg-light-blue-1">
            {{ $t('we_waiting_on_email_verification') }}
            <q-item class="bg-light-blue-1" :to="{name: 'home'}">
              <q-item-section class="text-center">
                {{ $t('home_page') }}
              </q-item-section>
            </q-item>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </div>
</template>

<script>
import Validator from 'src/plugins/tools/validator'
import Me from 'src/models/user/me'
import MeCookies from 'src/plugins/cookies/me'
import UserApi from 'src/plugins/api/user'
import Token from 'src/plugins/cookies/token'
import Api from 'src/plugins/api/api'

const formModel = {
  login: null,
  email: null,
  password: null,
  password_confirmation: null
}

export default {
  name: 'Register',

  data () {
    return {
      form: Object.assign({}, formModel),
      validator: new Validator(formModel),
      isSubmitting: false,
      userApi: new UserApi(),
      tokenCookies: new Token(),
      meCookies: new MeCookies(),
      api: new Api(),
      isDisplayEmailVerificationMessage: false
    }
  },

  methods: {
    submit () {
      this.isSubmitting = true
      this.userApi.register(this.form)
        .then(res => {
          this.tokenCookies.set(res.data.token)
          this.api.setBearer(this.tokenCookies.getAuthorizationToken())

          const me = res.data.user
          this.meCookies.set(me)
          Me.create({
            data: me
          })
          this.isSubmitting = false
          this.isDisplayEmailVerificationMessage = true
        })
        .catch(err => {
          this.validator.setErrors(err)
          this.isSubmitting = false
        })
    }
  }
}
</script>
