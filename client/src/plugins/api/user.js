import { api } from 'boot/axios'

export default class UserApi {
  constructor () {
    if (UserApi.instance) {
      return UserApi.instance
    }

    this.api = api

    UserApi.instance = this
  }

  fetchMe () {
    return new Promise((resolve, reject) => {
      this.api.get('/me')
        .then(res => resolve(res))
        .catch(err => {
          console.error(err)
          reject(reject)
        })
    })
  }
}
