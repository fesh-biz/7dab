import Api from 'src/plugins/api/api'

export default class Comment {
  constructor () {
    if (Comment.instance) {
      return Comment.instance
    }

    this.api = new Api()

    Comment.instance = this
  }

  async fetch (commentableId, commentableType) {
    try {
      return await this.api.get(
        'content/comments',
        {
          commentable_id: commentableId,
          commentable_type: commentableType
        })
    } catch (e) {
      console.error(e)
    }
  }

  create (data) {
    return new Promise((resolve, reject) => {
      this.api.post('content/comments', data)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  update (id, body) {
    return new Promise((resolve, reject) => {
      this.api.post(`content/comments/${id}`, {
        body: body
      })
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }
}
