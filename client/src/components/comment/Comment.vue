<template>
  <div
    class="comment"
    :id="'comment-' + comment.id"
  >
    <!-- Body -->
    <div style="padding-left: 5px; color: #5e5e5e">
      <!-- Rating, Author, Date -->
      <div class="flex items-center q-pl-sm">
        <rating
          :ratingable="comment"
          ratingable-type="comment"
        />

        <!-- Author -->
        <author
          :name="comment.user.login"
          :is-post-author="comment.user.id === postAuthor.id"
          :avatar="comment.user.avatar"
        />
      </div>

      <!-- Body -->
      <div style="margin: 10px 0 20px 10px">
        {{ comment.body }}
      </div>

      <add-comment
        is-reply
        :commentable-id="comment.id"
        commentable-type="comment"
        :post-id="postId"
      />
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
        :post-id="postId"
      />
    </div>
  </div>
</template>

<script>
import CommentModel from 'src/models/content/comment'
import Rating from 'components/rating/Rating'
import Author from 'components/common/Author'
import AddComment from 'components/comment/AddComment'

export default {
  name: 'Comment',

  props: {
    postId: {
      type: Number,
      required: true
    },
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
    AddComment,
    Author,
    Rating,
    Comment: () => import('./Comment.vue')
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
    },

    answers () {
      return CommentModel.query()
        .where(comment => {
          return comment.commentable_type_name === 'comments' &&
            comment.commentable_id === this.comment.id
        })
        .orderBy('id', 'desc')
        .with('rating')
        .with('user')
        .get()
    }
  },

  data () {
    return {
    }
  }
}
</script>
