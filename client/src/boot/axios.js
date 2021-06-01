import Vue from 'vue'
import axios from 'axios'
import { Cookies } from 'quasar'
import { routerInstance as router } from 'src/boot/router'
import Me from 'src/models/user/Me'

const api = axios.create({
  baseURL: process.env.API_URL
})

if (Cookies.get('bearer')) {
  api.defaults.headers.common.Authorization = Cookies.get('bearer')
}

Vue.prototype.$get = function (url, params) {
  return new Promise((resolve, reject) => {
    api.get(url, {
      params: {
        params
      }
    })
      .then(function (response) {
        resolve(response)
      })
      .catch(async function (error) {
        if (error.response && error.response.status === 401) {
          await Me.deleteAll()
          Cookies.remove('bearer')
          Cookies.remove('me')
          router.push({ name: 'login' })
        }

        reject(error)
      })
  })
}

Vue.prototype.$post = function (url, data, options) {
  return new Promise((resolve, reject) => {
    api.post(url, data, options)
      .then((response) => {
        resolve(response)
      })
      .catch(async function (error) {
        let isLoginUrl = false
        if (error.response && error.response.config && error.response.config.url) {
          const lastSegment = error.response.config.url.split('/').pop()
          isLoginUrl = lastSegment === 'login'
        }

        if (error.response && error.response.status === 401 && !isLoginUrl) {
          await Me.deleteAll()
          Cookies.remove('bearer')
          Cookies.remove('me')
          router.push({ name: 'login' })
        }

        reject(error)
      })
  })
}

Vue.prototype.$redirect = function (url) {
  window.location.href = url
}

export { api }
