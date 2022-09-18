import { api } from 'boot/axios'
import { Notify } from 'quasar'
import { i18n } from 'boot/i18n'

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

    if (['invalid_grant', 'invalid_request'].includes(error)) return

    const message = i18n.getLocaleMessage(i18n.locale).something_went_wrong.toString()

    Notify.create({
      message: message,
      position: 'center',
      color: 'negative'
    })
  }
}
