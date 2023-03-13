import axios from 'axios'
import { Notify } from 'quasar'
import { i18n } from 'boot/i18n'
import Token from 'src/plugins/cookies/token'
import Me from 'src/plugins/cookies/me'
import Cache from 'src/plugins/cache/cache'

export default class Api {
  constructor () {
    if (Api.instance) {
      return Api.instance
    }

    this.axios = axios.create({
      baseURL: process.env.API_URL,
      headers: { 'Content-Type': 'application/json' }
    })

    this.tokenCookies = new Token()
    this.meCookies = new Me()
    this.cache = new Cache()

    if (this.tokenCookies.getAuthorizationToken()) {
      this.setBearer(this.tokenCookies.getAuthorizationToken())
    }

    Api.instance = this
  }

  setBearer (bearer) {
    this.axios.defaults.headers.common.Authorization = bearer
  }

  deleteBearer () {
    delete this.axios.defaults.headers.common.Authorization
  }

  get (url, params, ormTableName, bypassCacheStoring) {
    return new Promise((resolve, reject) => {
      return this.request(url, 'get', params, ormTableName, bypassCacheStoring)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  post (url, data, options, ormTableName, bypassCacheStoring) {
    return new Promise((resolve, reject) => {
      return this.request(url, 'post', data, ormTableName, bypassCacheStoring)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  put (url, data, options, ormTableName, bypassCacheStoring) {
    return new Promise((resolve, reject) => {
      return this.request(url, 'put', data, ormTableName, bypassCacheStoring)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  request (url, method, params, ormTableName, bypassCacheStoring) {
    if (process.env.ENV_DEV === 'Development') {
      console.log('----rqst start-----')
      console.log('requesting url:', url)
      console.log('method:', method)
      console.log('params:', params)
      console.log('ormTableName:', ormTableName)
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
        .then(async res => {
          if (res.data && !bypassCacheStoring) {
            if (!ormTableName) {
              throw new Error(
                'The ormTableName value is required!' +
                'Check request with url ' + url + ' method ' + method
              )
            }

            if (typeof ormTableName !== 'string') {
              throw new Error(
                'The ormTableName value is not a string. ' +
                'Check request with url ' + url + ' method ' + method
              )
            }

            await this.cache.setPageCache(res, ormTableName)
          }

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

          console.log(err)
          this.showError(err)

          reject(err)
        })
    })
  }

  showError (error) {
    if (typeof error.message === 'string') {
      console.error(error.message)
    } else {
      error = error.response.data.error

      if (['invalid_grant', 'invalid_request'].includes(error)) return
    }

    let message = i18n.getLocaleMessage(i18n.locale).something_went_wrong.toString()

    if (error.response.status === 422) {
      message = i18n.getLocaleMessage(i18n.locale).check_your_data.toString()
    }

    Notify.create({
      message: message,
      position: 'center',
      color: 'negative'
    })
  }
}
