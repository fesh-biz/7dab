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
      isFetching: true
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
    const res = await this.api.fetch(this.postId, 'post')
    await CommentModel.insert({
      data: res.data
    })
    this.isFetching = false
  },

  beforeDestroy () {
    CommentModel.deleteAll()
  }
}
</script>
