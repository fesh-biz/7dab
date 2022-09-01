import { api } from 'boot/axios'

export default class PostApi {
  constructor () {
    if (PostApi.instance) {
      return PostApi.instance
    }

    this.api = api

    PostApi.instance = this
  }

  store (data) {
    return new Promise((resolve, reject) => {
      this.api.post('/content/posts', data)
        .then(res => resolve(res))
        .catch(err => {
          console.error(err)
          reject(err)
        })
    })
  }
}
