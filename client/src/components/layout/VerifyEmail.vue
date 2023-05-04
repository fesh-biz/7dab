<template>
  <q-dialog v-model="confirm" persistent>
    <q-card>
      <!-- Sending button, Info, Check Button-->
      <template v-if="!isSuccess">
        <!-- Sending button -->
        <q-card-section>
          <p>Спочатку вам необхідно підтвердити ваш Email.</p>

          <q-btn
            no-caps
            label="Надіслати посилання"
            color="positive"
            :loading="sendingVerification"
            :disable="sendingVerification || isSent"
            @click="sendVerification"
          />
        </q-card-section>

        <!-- Info -->
        <q-card-section v-if="isSent">
          Ми надіслали вам інструкцію на ваш email для підтвердження.
          Якщо ви не бачите листа, <strong>перевірте вашу папку для спаму</strong>.
        </q-card-section>

        <!-- Check Button -->
        <q-card-section v-if="isSent">
          <p>Щоб оновити дані:</p>
          <q-btn
            label="Оновити"
            :loading="isFetchingMe"
            :disable="isFetchingMe || isFetchedMe"
            color="positive"
            @click="checkEmailIsVerified"
          />
        </q-card-section>
      </template>

      <q-card-section v-if="isSuccess">
        <h2>Усе ок!</h2>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn
          no-caps
          :label="isSuccess ? 'Ок' : 'Скасувати'"
          :color="isSuccess ? 'positive' : 'negative'"
          @click="reset"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import UserApi from 'src/plugins/api/user'
import MeModel from 'src/models/user/me'
import MeCookies from 'src/plugins/cookies/me'

export default {
  name: 'VerifyEmail',

  data () {
    return {
      confirm: false,
      sendingVerification: false,
      api: new UserApi(),
      isSent: false,
      isFetchingMe: false,
      isFetchedMe: false,
      meCookies: new MeCookies(),
      isSuccess: false
    }
  },

  created () {
    this.$root.$on('verify-email', () => {
      this.confirm = true
    })
  },

  methods: {
    reset () {
      this.confirm = false
      this.sendingVerification = false
      this.isSent = false
      this.isFetchingMe = false
      this.isFetchedMe = false
      this.isSuccess = false
    },

    async sendVerification () {
      this.sendingVerification = true
      await this.api.resendEmailVerification()
      this.sendingVerification = false
      this.isSent = true
    },

    async checkEmailIsVerified () {
      this.isFetchingMe = true
      const res = await this.api.fetchMe()
      const me = res.data

      if (me.is_verified) {
        await MeModel.create({
          data: me
        })
        this.meCookies.set(me)
      }

      this.isSuccess = true
      this.isFetchingMe = false
      this.isFetchedMe = true
    }
  }
}
</script>
