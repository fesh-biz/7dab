import { api } from 'boot/axios'
import { Notify } from 'quasar'

export default class Api {
  constructor () {
    if (Api.instance) {
      return Api.instance
    }

    this.api = api

    Api.instance = this
  }

  get (url, config) {
    return new Promise((resolve, reject) => {
      this.api.get(url, config)
        .then(res => resolve(res))
        .catch(err => {
          this.showError(err)
          reject(err)
        })
    })
  }

  post (url, data, config) {
    return new Promise((resolve, reject) => {
      this.api.post(url, data, config)
        .then(res => resolve(res))
        .catch(err => {
          this.showError(err)
          reject(err)
        })
    })
  }

  put (url, data, config) {
    return new Promise((resolve, reject) => {
      this.api.put(url, data, config)
        .then(res => resolve(res))
        .catch(err => {
          this.showError(err)
          reject(err)
        })
    })
  }

  showError (response) {
    const error = response.response.data.error

    console.log('error', response.response)

    if (['invalid_grant', 'invalid_request'].includes(error)) return

    Notify.create({
      message: response.response.data.message,
      position: 'center',
      color: 'negative'
    })
  }
}
