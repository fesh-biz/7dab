<template>
  <div>
    <post-comment
      v-for="(comment, index) in comments"
      :key="comment.id"
      :comment="comment"
      :level="1"
      :is-last="comments.length - 1 === index"
      :post-author="postAuthor"
    />
  </div>
</template>

<script>
import PostComment from 'components/content/PostComment'
import Comment from 'src/plugins/api/comment'
import CommentModel from 'src/models/content/comment'
import PostModel from 'src/models/content/post'

export default {
  name: 'PostComments',

  components: {
    PostComment
  },

  props: {
    postId: {
      type: Number,
      required: true
    }
  },

  data () {
    return {
      api: new Comment()
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
    const res = await this.api.fetch(this.postId, 'post')
    console.log('res', res)

    await CommentModel.create({
      data: res.data
    })
  }
}
</script>
