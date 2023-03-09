import Api from 'src/plugins/api/api'

export default class Post {
  constructor () {
    if (Post.instance) {
      return Post.instance
    }

    this.api = new Api()

    Post.instance = this
  }

  incrementViews (id) {
    return new Promise((resolve, reject) => {
      this.api.post(`content/posts/increment-views/${id}`)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  fetchPost (id) {
    return new Promise((resolve, reject) => {
      this.api.get(`content/posts/${id}`, null, 'posts')
        .then(res => {
          resolve(res)
        })
        .catch(err => {
          reject(err)
        })
    })
  }

  fetchPosts (page) {
    return new Promise((resolve, reject) => {
      this.api.get(`/content/posts?page=${page}`, null, 'posts')
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
        '/content/posts',
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
        `/content/posts/${id}`,
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
}
