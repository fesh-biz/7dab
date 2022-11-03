<template>
  <div class="row justify-center">
    <div class="col-sm-12 col-xs-12 col-md-6 col-lg-4 col-xl-4">
      <div class="flex column justify-center q-pa-md" style="height: 100vh">
        <q-card>
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
                  v-model="form.name"
                  :label="$t('name')"

                  :error="!!validator.errors.name"
                  :error-message="validator.errors.name"

                  @input="validator.resetFieldError('name')"
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
          <q-card-section>
            <q-btn
                dusk="r-registration-button"
                color="primary"
                :loading="isSubmitting"
                :disable="isSubmitting"
                :label="$t('register')"
                @click="submit"
            />
          </q-card-section>
        </q-card>
      </div>
    </div>
  </div>
</template>

<script>
import Validator from 'src/plugins/tools/validator'
import Me from 'src/models/user/Me'
import UserApi from 'src/plugins/api/user'
import TokenCookies from 'src/plugins/cookies/token-cookies'
import { api } from 'boot/axios'

const formModel = {
  name: null,
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
      tokenCookies: new TokenCookies()
    }
  },

  methods: {
    submit () {
      this.userApi.register(this.form)
        .then(res => {
          this.tokenCookies.set(res.data.token)
          api.defaults.headers.common.Authorization = this.tokenCookies.getAuthorizationToken()
          Me.create({
            data: res.data.user
          })
          this.$router.push({ name: 'home' })
        })
        .catch(err => {
          this.validator.setErrors(err)
        })
    }
  }
}
</script>
