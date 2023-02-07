import axios from 'axios'
import { Notify } from 'quasar'
import { i18n } from 'boot/i18n'
import Token from 'src/plugins/cookies/token'
import Me from 'src/plugins/cookies/me'

export default class Api {
  constructor () {
    this.axios = axios.create({
      baseURL: process.env.API_URL,
      headers: { 'Content-Type': 'application/json' }
    })

    this.tokenCookies = new Token()
    this.meCookies = new Me()

    if (this.tokenCookies.getAuthorizationToken()) {
      this.axios.defaults.headers.common.Authorization = this.tokenCookies.getAuthorizationToken()
    }
  }

  get (url, params) {
    return new Promise((resolve, reject) => {
      return this.request(url, 'get', params)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  post (url, data) {
    return new Promise((resolve, reject) => {
      return this.request(url, 'post', data)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  put (url, data) {
    return new Promise((resolve, reject) => {
      return this.request(url, 'put', data)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  request (url, method, params) {
    if (process.env.ENV_DEV === 'Development') {
      console.log('----rqst start-----')
      console.log('requesting url:', url)
      console.log('method:', method)
      console.log('params:', params)
      console.log('----rqst end-----')
    }

    const conf = {
      url: url,
      method: method
    }

    if (['post', 'put'].includes(method)) {
      conf.data = params
    } else {
      conf.params = params
    }

    return new Promise((resolve, reject) => {
      this.axios(conf)
        .then(res => {
          if (process.env.ENV_DEV === 'Development') {
            console.log('res', res)
          }
          resolve(res)
        })
        .catch(err => {
          if (err.response && err.response.status === 401) {
            this.tokenCookies.delete()
            this.meCookies.delete()
          }

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
