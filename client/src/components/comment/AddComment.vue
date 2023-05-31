<template>
  <q-card flat style="color: darkslategray" class="q-mt-lg q-mb-lg">
    <!-- Open Comment Form Button -->
    <q-card-actions @click="isOpened = true" v-if="isReply && !isExpanded" class="q-ml-sm">
      <q-btn dense no-caps color="light-green-8">{{ $t('to_reply') }}</q-btn>
    </q-card-actions>

    <!-- Comment Form -->
    <template v-if="isExpanded">
      <!-- Input -->
      <q-card-section>
        <div>{{ isReply ? $t('to_reply') : $t('add_comment') }}</div>

        <q-input
          autogrow
          outlined
          :autofocus="isReply"
          @focus="checkAuth"
          ref="input"
          v-model="formModel.body"
          :error="!!validator.errors.body"
          :error-message="validator.errors.body"
          @input="validator.resetErrors()"
          @blur="onInputBlur"
        />
      </q-card-section>

      <select-fake-user v-if="me && me.id === 1" v-model="fakeUserId"/>

      <q-separator />

      <q-card-section
        v-if="showUnauthMessage"
      >
        <p>
          <strong style="color: red">{{ $t('need_to_login_or_register') }}</strong>
        </p>

        <q-btn
          dense
          no-caps
          :to="{name: 'login'}"
          label="Увійти"
        />

        <q-btn
          dense
          no-caps
          class="q-ml-sm"
          :to="{name: 'register'}"
          label="Зареєструватись"
        />
      </q-card-section>

      <!-- Submit Button -->
      <q-card-actions class="q-ml-sm">
        <q-btn
          :loading="isSubmitting"
          :disable="isSubmitting"
          dense
          no-caps
          color="light-green-8"
          @click="submit"
        >
          {{ $t('submit') }}
        </q-btn>
      </q-card-actions>
    </template>
  </q-card>
</template>

<script>
import Me from 'src/models/user/me'
import Comment from 'src/plugins/api/comment'
import _ from 'lodash'
import Validator from 'src/plugins/tools/validator'
import CommentModel from 'src/models/content/comment'
import Scroll from 'src/plugins/tools/scroll'
import SelectFakeUser from 'components/fake-user/SelectFakeUser'

const formModel = {
  body: null
}

export default {
  name: 'AddComment',
  components: { SelectFakeUser },
  props: {
    isReply: {
      type: Boolean,
      default: false
    },
    commentableId: {
      type: Number,
      required: true
    },
    commentableType: {
      type: String,
      required: true
    },
    postId: {
      type: Number,
      required: true
    }
  },

  data () {
    return {
      fakeUserId: null,
      formModel: _.cloneDeep(formModel),
      isOpened: false,
      isSubmitting: false,
      api: new Comment(),
      validator: new Validator(formModel),
      scroll: new Scroll(),
      showUnauthMessage: false
    }
  },

  computed: {
    isExpanded () {
      if (!this.isReply) return true

      return this.isOpened
    },

    me () {
      return Me.query().first()
    }
  },

  methods: {
    onInputBlur () {
      setTimeout(() => {
        this.showUnauthMessage = false
      }, 500)
    },

    checkAuth () {
      let res = true

      if (!this.me) {
        this.showUnauthMessage = true
        res = false
      } else if (!this.me.is_verified) {
        this.$refs.input.blur()
        this.$root.$emit('verify-email')
        res = false
      }

      return res
    },

    submit () {
      if (!this.checkAuth()) return

      this.isSubmitting = true

      const data = {
        post_id: this.postId,
        commentable_id: this.commentableId,
        commentable_type: this.commentableType,
        body: this.formModel.body
      }

      if (this.me.id === 1 && this.fakeUserId) {
        data.fake_user_id = this.fakeUserId
      }

      this.api.create(data)
        .then((res) => {
          this.formModel = _.cloneDeep(formModel)
          CommentModel.insert({
            data: res.data
          })

          this.$q.notify({
            message: this.$t('success'),
            position: 'center',
            color: 'positive'
          })

          this.isSubmitting = false

          if (this.isReply) {
            this.isOpened = false
          }

          setTimeout(() => {
            const element = document.getElementById('comment-' + res.data.id)
            element.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' })

            this.scroll.whenScrollFinished()
              .then(() => {
                this.flashComment(res.data.id)
              })
          }, 10)
        })
        .catch(err => {
          this.validator.setErrors(err)
          this.isSubmitting = false
        })
    },

    flashComment (id) {
      const element = document.getElementById('comment-' + id)

      const rect = element.getBoundingClientRect()
      const isVisible = rect.top < window.innerHeight && rect.bottom >= 0
      if (isVisible) {
        element.classList.add('flash')
        setTimeout(() => {
          element.classList.remove('flash')
        }, 2000) // remove the class after 2 seconds
      }
    }
  }
}
</script>
