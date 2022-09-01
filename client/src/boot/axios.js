import axios from 'axios'
import TokenCookies from 'src/plugins/cookies/tokenCookies'

const api = axios.create({
  baseURL: process.env.API_URL
})

const tokenCookies = new TokenCookies()

if (tokenCookies.getAuthorizationToken()) {
  api.defaults.headers.common.Authorization = tokenCookies.getAuthorizationToken()
}

export { api }
