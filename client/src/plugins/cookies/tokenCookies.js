import { Cookies } from 'quasar'

export default class TokenCookies {
  constructor () {
    if (TokenCookies.instance) {
      return TokenCookies.instance
    }

    TokenCookies.instance = this
  }

  delete () {
    Cookies.remove('token')
  }

  getAuthorizationToken () {
    let res = null

    const token = this.getCookiesData()

    if (token && token.tokenType && token.accessToken) {
      res = `${token.tokenType} ${token.accessToken}`
    }

    return res
  }

  getCookiesData () {
    return JSON.parse(Cookies.get('token'))
  }

  set (tokenData) {
    tokenData = JSON.stringify({
      accessToken: tokenData.access_token,
      expiresIn: tokenData.expires_in,
      refreshToken: tokenData.refresh_token,
      tokenType: tokenData.token_type
    })

    Cookies.set('token', tokenData, { path: '/' })
  }
}
