import axios from 'axios'
import TokenCookies from 'src/plugins/cookies/token-cookies'

const api = axios.create({
  baseURL: process.env.API_URL
})

const tokenCookies = new TokenCookies()

if (tokenCookies.getAuthorizationToken()) {
  api.defaults.headers.common.Authorization = tokenCookies.getAuthorizationToken()
}

export { api }
