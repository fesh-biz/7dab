import Api from 'src/plugins/api/api'

export default class Comment {
  constructor () {
    if (Comment.instance) {
      return Comment.instance
    }

    this.api = new Api()

    Comment.instance = this
  }

  async fetchPostComments (postId) {
    try {
      return await this.api.get(
        `posts/${postId}/comments`,
        null,
        'comments'
      )
    } catch (e) {
      console.error(e)
    }
  }

  create (data) {
    return new Promise((resolve, reject) => {
      this.api.post('comments', data, null, 'comments')
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  update (id, body) {
    return new Promise((resolve, reject) => {
      this.api.post(`content/comments/${id}`, {
        body: body
      },
      null,
      'comments'
      )
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }
}
