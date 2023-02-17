<template>
  <div>
    <post-comment />
  </div>
</template>

<script>
import PostComment from 'components/content/PostComment'
import Comment from 'src/plugins/api/comment'
import CommentModel from 'src/models/content/comment'

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

  async created () {
    const res = await this.api.fetch(this.postId, 'post')
    console.log('res', res)

    await CommentModel.create({
      data: res.data
    })

    const comments = CommentModel.query()
      .where(comment => {
        return comment.commentable_type_name === 'posts' &&
          comment.commentable_id === this.postId
      })
      .withAllRecursive().get()
    console.log(comments)
  }
}
</script>
