import axios from 'axios'
import Token from 'src/plugins/cookies/token'

const api = axios.create({
  baseURL: process.env.API_URL
})

const tokenCookies = new Token()

if (tokenCookies.getAuthorizationToken()) {
  api.defaults.headers.common.Authorization = tokenCookies.getAuthorizationToken()
}

export { api }
