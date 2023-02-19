<template>
  <div
    class="comment"
  >
    <!-- Body -->
    <div style="padding-left: 5px; color: #5e5e5e">
      <!-- Rating, Author, Date -->
      <div class="flex items-center q-pl-sm">
        <rating
          :ratingable-type="'comment'"
          :ratingable="comment"
        />

        <!-- Author -->
        <author
          :name="comment.author.login"
          :is-post-author="comment.author.id === postAuthor.id"
          :avatar="comment.author.avatar"
        />
      </div>

      <!-- Body -->
      <div style="margin: 10px 10px 0 10px">
        {{ comment.body }}

        <div style="border-bottom: 1px solid darkgrey; margin-left: 50%; margin-bottom: 15px"></div>
      </div>
    </div>

    <!-- Answers -->
    <div
      v-if="answers.length"
      :style="{borderLeft: levelBorderColor,}"
      style="margin-left: 10px"
    >
      <comment
        v-for="(comment, index) in answers"
        :key="comment.id"
        :comment="comment"
        :level="level + 1"
        :is-last="answers.length - 1 === index && !isLast"
        :post-author="postAuthor"
      />
    </div>
  </div>
</template>

<script>
import CommentModel from 'src/models/content/comment'
import Rating from 'components/common/Rating'
import Author from 'components/common/Author'

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
    Author,
    Rating,
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

      return '1px solid ' + colors[(this.level + 2) % 10]
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
