<template>
  <div v-if="post">
    <p>
      Теревенька має статус: <strong>{{ $t(post.status) }}</strong>
    </p>

    <div v-if="post.isDraft">
      <p>
        Ви можете продовжити редагування.
      </p>
      <p>
        Щоб відправити теревеньку на перевірку, нажміть на <strong>Підтвердити публікацію</strong>. <br />
        <strong>Увага:</strong> <br />
        1. Ця дія не зберігає останні зроблені зміни. Щоб зробити це, натисніть зелену галочку знизу. <br />
        2. Після цієї дії ви не зможете редагувати або видалити цю теревеньку.
      </p>
      <!-- Confirm publication -->
      <q-btn
        dense
        :loading="isPublishing"
        :disable="isPublishing"
        no-caps
        color="positive"
        @click="showConfirmPublication = true"
        label="Підтвердити публікацію"
      />
      <confirm
        v-model="showConfirmPublication"
        title="Підтвердити публікацію"
        message="Не забудьте зберегти останні зміни перед цією дією. Після цієї дії ви не зможете редагувати або
        видалити цю теревеньку."
        :on-confirm="onPublish"
      />

      <!-- Delete post -->
      <q-btn
        class="q-ml-md"
        dense
        no-caps
        color="negative"
        @click="showConfirmDelete = true"
        label="Видалити Теревеньку"
      />
      <confirm
        v-model="showConfirmDelete"
        title="Підтвердити видалення"
        message="Ви впевнені? Цю дію неможливо буде відмінити."
        :on-confirm="onDelete"
      />
    </div>

    <div v-if="!post.isDraft">
      <p v-if="post.isApproved">
        Вітаємо, ваша теревенька опублікована!
      </p>

      <p v-if="post.isDeclined">
        На жаль, ми відхилили запит на публікацію цієї теревеньки.
      </p>

      <p v-if="post.isEditing">
        Ми редагуємо вашу теревеньку. Зміст не буде змінено, лише виправлені помилки.
      </p>

      <p><strong>Редагування не діє. Зміни не будуть збережені.</strong></p>
    </div>
  </div>
</template>

<script>
import Post from 'src/models/content/post'
import Confirm from 'components/common/Confirm'
import PostApi from 'src/plugins/api/post'

export default {
  name: 'PostStatusExplanation',

  components: {
    Confirm
  },

  props: {
    postId: {
      type: [String, Number],
      required: true
    }
  },

  data () {
    return {
      showConfirmPublication: false,
      showConfirmDelete: false,
      api: new PostApi(),
      isPublishing: false
    }
  },

  computed: {
    post () {
      return Post.query().find(this.postId)
    }
  },

  methods: {
    async onPublish () {
      try {
        this.isPublishing = true
        await this.api.publish(this.postId)
        this.isPublishing = false
        await Post.update({
          where: this.postId,
          data: { status: 'pending' }
        })
        this.$q.notify({
          position: 'center',
          color: 'positive',
          message: this.$t('success')
        })
      } catch (e) {
        this.isPublishing = false
      }
    },

    onDelete () {
      console.log('deleting')
    }
  }
}
</script>
