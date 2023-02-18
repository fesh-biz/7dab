<template>
  <div
    :style="{
      borderLeft: '1px solid ' + levelBorderColor,
      marginLeft: level === 1 ? 0 : 10 + 'px'
    }"
    class="comment"
  >
    <!-- Body -->
    <div style="padding-left: 5px; color: #5e5e5e">
      <!-- Author -->
      <div>
        <q-chip class="glossy" :color="comment.author.id === postAuthor.id ? 'green-2' : 'light-blue-2'" size="0.8rem">
          <q-avatar>
            <q-icon name="account_circle"/>
          </q-avatar>
          <strong>{{ comment.author.login }}</strong>
        </q-chip>
      </div>

      <!-- Body -->
      <div style="margin-left: 10px">
        {{ comment.body }}

        <div style="border-bottom: 1px solid darkgrey; margin-left: 50%; margin-bottom: 15px"></div>
      </div>
    </div>

    <comment
      v-for="(comment, index) in answers"
      :key="comment.id"
      :comment="comment"
      :level="level + 1"
      :is-last="answers.length - 1 === index && !isLast"
      :post-author="postAuthor"
    />
  </div>
</template>

<script>
import CommentModel from 'src/models/content/comment'

export default {
  name: 'PostComment',

  props: {
    comment: {
      type: Object,
      required: true
    },
    level: {
      type: Number,
      required: true
    },
    isLast: {
      type: Boolean,
      required: true
    },
    postAuthor: {
      type: Object,
      required: true
    }
  },

  components: {
    Comment: () => import('./PostComment.vue')
  },

  computed: {
    levelBorderColor () {
      const colors = [
        '#0d47a1',
        '#1565c0',
        '#1976d2',
        '#1e88e5',
        '#2196f3',
        '#42a5f5',
        '#64b5f6',
        '#90caf9',
        '#bbdefb',
        '#e3f2fd'
      ]

      return colors[(this.level + 2) % 10]
    }
  },

  data () {
    return {
      answers: []
    }
  },

  created () {
    this.answers = CommentModel.query()
      .where(comment => {
        return comment.commentable_type_name === 'comments' &&
          comment.commentable_id === this.comment.id
      })
      .withAll()
      .get()
  }
}
</script>

<style lang="sass">
.comment
  //background-color: #00b0ff
</style>
