<template>
  <q-card
    flat
    :bordered="!$q.platform.is.mobile"
    class="q-mb-xl"
  >
    <q-card-section>
      <!-- Add a Comment -->
      <add-comment
        :commentable-id="postId"
        commentable-type="post"
        :post-id="postId"
      />

      <q-linear-progress
        v-if="isFetching"
        indeterminate
        style="margin-top: -4px"
      />

      <!-- No Comments -->
      <div
        v-if="!isFetching && !comments.length"
        class="text-center"
      >
        {{ $t('there_are_no_comments_yet') }}
      </div>

      <comment
        v-for="(comment, index) in comments"
        :key="comment.id"
        :comment="comment"
        :post-id="postId"
        :level="1"
        :is-last="comments.length - 1 === index"
        :post-author="postAuthor"
      />
    </q-card-section>
  </q-card>
</template>

<script>
import Comment from 'components/comment/Comment'
import CommentApi from 'src/plugins/api/comment'
import CommentModel from 'src/models/content/comment'
import PostModel from 'src/models/content/post'
import AddComment from 'components/comment/AddComment'
import Scroll from 'src/plugins/tools/scroll'

export default {
  name: 'PostComments',

  components: {
    AddComment,
    Comment
  },

  props: {
    postId: {
      type: Number,
      required: true
    }
  },

  data () {
    return {
      temp: '',
      api: new CommentApi(),
      isFetching: true,
      scroll: new Scroll()
    }
  },

  computed: {
    comments () {
      return CommentModel.query()
        .where(comment => {
          return comment.commentable_type_name === 'posts' &&
            comment.commentable_id === this.postId
        })
        .orderBy('id', 'desc')
        .with('rating')
        .with('user')
        .get()
    },

    postAuthor () {
      return PostModel.query().with('user').find(this.postId).user
    }
  },

  async created () {
    const res = await this.api.fetchPostComments(this.postId)
    await CommentModel.insert({
      data: res.data
    })
    this.isFetching = false

    const commentId = this.$route.query.c
    if (commentId) {
      this.$nextTick(() => {
        const element = document.getElementById('comment-' + commentId)

        if (!element) return

        element.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' })

        this.scroll.whenScrollFinished()
          .then(() => {
            this.flashComment(commentId)
          })
      })
    }
  },

  methods: {
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
  },

  beforeDestroy () {
    CommentModel.deleteAll()
  }
}
</script>
