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
              <q-btn dusk="l-login-form-submit" :disable="formIsBusy" :loading="formIsBusy" :label="$t('log_in')" type="submit" color="primary"/>
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
      validator: new Validator(formModel)
    }
  },

  created () {
    delete api.defaults.headers.common.Authorization
  },

  methods: {
    onSubmit () {
      this.formIsBusy = true
      this.$post('token', {
        grant_type: 'password',
        client_id: process.env.CLIENT_ID,
        client_secret: process.env.CLIENT_SECRET,
        username: this.form.email,
        password: this.form.password
      }).then((res) => {
        this.formIsBusy = false
        const bearer = 'Bearer ' + res.data.access_token
        api.defaults.headers.common.Authorization = bearer
        this.$q.cookies.set('bearer', bearer, { path: '/' })

        this.$get('/me')
          .then(res => {
            Me.create({
              data: res.data
            })
            this.$q.cookies.set('me', res.data, { path: '/' })
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
