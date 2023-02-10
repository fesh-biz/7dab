import { Cookies } from 'quasar'

export default class Token {
  constructor () {
    if (Token.instance) {
      return Token.instance
    }

    Token.instance = this
  }

  delete () {
    Cookies.remove('token', { path: '/' })
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
    return Cookies.get('token')
  }

  getIsExpired () {
    let res = false

    const token = this.getCookiesData()

    if (token && token.expiresIn) {
      const now = Math.floor(Date.now() / 1000)
      const day = 60 * 60 * 24

      if (token.expiresIn - now < day) {
        res = true
      }
    }

    return res
  }

  set (tokenData) {
    tokenData = JSON.stringify({
      accessToken: tokenData.access_token,
      expiresIn: Math.floor(Date.now() / 1000) + tokenData.expires_in,
      refreshToken: tokenData.refresh_token,
      tokenType: tokenData.token_type
    })

    Cookies.set('token', tokenData, { path: '/' })
  }
}
