<template>
  <q-card
    flat
    :bordered="!$q.platform.is.mobile"
  >
    <q-card-section>
      <q-linear-progress
        v-if="isFetching"
        indeterminate
        style="margin-top: -4px"
      />

      <comment
        v-for="(comment, index) in comments"
        :key="comment.id"
        :comment="comment"
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

export default {
  name: 'PostComments',

  components: {
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
        .withAll()
        .get()
    },

    postAuthor () {
      return PostModel.query().with('user').find(this.postId).user
    }
  },

  async created () {
    await CommentModel.deleteAll()

    const res = await this.api.fetch(this.postId, 'post')
    await CommentModel.create({
      data: res.data
    })
    this.isFetching = false
  }
}
</script>
