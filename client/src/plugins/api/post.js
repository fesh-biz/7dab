import Api from 'src/plugins/api/api'

export default class Post {
  constructor () {
    if (Post.instance) {
      return Post.instance
    }

    this.api = new Api()

    Post.instance = this
  }

  incrementPostViewsCounter (id) {
    return new Promise((resolve, reject) => {
      this.api.post(`posts/increment-views/${id}`)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  fetchPostView (id) {
    return new Promise((resolve, reject) => {
      this.api.get(`posts/${id}`, null, 'posts')
        .then(res => {
          resolve(res)
        })
        .catch(err => {
          reject(err)
        })
    })
  }

  fetchPostPreview (id) {
    return new Promise((resolve, reject) => {
      this.api.get(`posts/${id}/preview`, null, 'posts')
        .then(res => {
          resolve(res)
        })
        .catch(err => {
          reject(err)
        })
    })
  }

  store (data) {
    return new Promise((resolve, reject) => {
      this.api.post(
        'posts',
        data,
        { headers: { 'Content-Type': 'multipart/form-data' } },
        'posts'
      )
        .then(res => resolve(res))
        .catch(err => {
          reject(err)
        })
    })
  }

  update (data, id) {
    return new Promise((resolve, reject) => {
      this.api.post(
        `/posts/${id}`,
        data,
        { headers: { 'Content-Type': 'multipartddd/form-data' } },
        'posts'
      )
        .then(res => resolve(res))
        .catch(err => {
          reject(err)
        })
    })
  }

  publish (id) {
    return new Promise((resolve, reject) => {
      this.api.post(
        `/content/posts/${id}/publish`, null, null, null, true)
        .then(res => resolve(res))
        .catch(err => {
          reject(err)
        })
    })
  }

  delete (id) {
    return new Promise((resolve, reject) => {
      this.api.post(
        `/content/posts/${id}/delete`, null, null, null, true)
        .then(res => resolve(res))
        .catch(err => {
          reject(err)
        })
    })
  }
}
