import Api from 'src/plugins/api/api'

export default class PostApi {
  constructor () {
    if (PostApi.instance) {
      return PostApi.instance
    }

    this.api = new Api()

    PostApi.instance = this
  }

  fetchPost (id) {
    return new Promise((resolve, reject) => {
      this.api.get(`content/posts/${id}`)
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
      this.api.get(`/content/posts?page=${page}`)
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
      this.api.post('/content/posts', data)
        .then(res => resolve(res))
        .catch(err => {
          reject(err)
        })
    })
  }
}
