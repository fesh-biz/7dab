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
          type="textarea"
          v-model="model"
          autogrow
          outlined
          @focus="checkAuth"
        />
      </q-card-section>

      <q-separator />

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

export default {
  name: 'AddComment',

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
      model: '',
      isOpened: false,
      isSubmitting: false,
      api: new Comment()
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
    checkAuth () {
      if (!this.me) {
        this.showUnauthMessage()
        return false
      }

      return true
    },

    submit () {
      if (!this.checkAuth()) return

      this.isSubmitting = true
      this.api.create(this.postId, this.commentableId, this.commentableType, this.model)
        .then(() => {
          console.log('yes')

          this.isSubmitting = false
        })
        .catch(err => {
          console.log('err', err.response)
          this.isSubmitting = false
        })
    },

    showUnauthMessage () {
      this.$q.notify({
        message: this.$t('need_to_login_or_register'),
        position: 'center',
        color: 'negative'
      })
    }
  }
}
</script>
