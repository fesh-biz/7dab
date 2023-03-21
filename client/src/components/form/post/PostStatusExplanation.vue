<template>
  <div v-if="post">
    <p>
      Пост зараз має статус: <strong>{{ $t(post.status) }}</strong>
    </p>

    <div v-if="post.status === 'draft'">
      <p>
        Ви можете продовжити редагування.
      </p>
      <p>
        Щоб відправити теревеньку на перевірку, нажміть на <strong>Підтвердити публікацію</strong>. <br />
        <strong>Увага:</strong> Після цієї дії ви не зможете редагувати або
        видалити цю теревеньку.
      </p>
      <!-- Confirm publication -->
      <q-btn dense no-caps color="positive" @click="showConfirmPublication = true" label="Підтвердити публікацію"/>
      <confirm
        v-model="showConfirmPublication"
        title="Підтвердити публікацію"
        message="Після цієї дії ви не зможете редагувати або
        видалити цю теревеньку."
        :on-confirm="onPublish"
      />

      <!-- Delete post -->
      <q-btn class="q-ml-md" dense no-caps color="negative" @click="showConfirmDelete = true" label="Видалити Теревеньку"/>
      <confirm
        v-model="showConfirmDelete"
        title="Підтвердити видалення"
        message="Ви впевнені? Цю дію неможливо буде відмінити."
        :on-confirm="onDelete"
      />
    </div>

    <div v-if="post.status !== 'draft'">
      <p v-if="post.status === 'approved'">
        Вітаємо, ваша теревенька опублікована.
      </p>

      <p>Редагування не діє. Зміни не будуть збережені.</p>
    </div>
  </div>
</template>

<script>
import Post from 'src/models/content/post'
import Confirm from 'components/common/Confirm'

export default {
  name: 'PostStatusExplanation',

  components: {
    Confirm
  },

  props: {
    postId: {
      type: String,
      required: true
    }
  },

  data () {
    return {
      showConfirmPublication: false,
      showConfirmDelete: false
    }
  },

  computed: {
    post () {
      return Post.query().find(this.postId)
    }
  },

  methods: {
    onPublish () {
      console.log('publish')
    },

    onDelete () {
      console.log('deleting')
    }
  }
}
</script>
