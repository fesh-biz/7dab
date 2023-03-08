import Api from 'src/plugins/api/api'

export default class TokenApi {
  constructor () {
    if (TokenApi.instance) {
      return TokenApi.instance
    }

    this.api = new Api()

    this.token = null
    this.expiresIn = null

    TokenApi.instance = this
  }

  createToken (username, password) {
    return new Promise((resolve, reject) => {
      this.api.post(
        'token',
        {
          grant_type: 'password',
          client_id: process.env.CLIENT_ID,
          client_secret: process.env.CLIENT_SECRET,
          username: username,
          password: password
        },
        null,
        null,
        true
      )
        .then(res => resolve(res))
        .catch(err => {
          reject(err)
        })
    })
  }

  refreshToken () {
    return new Promise((resolve, reject) => {
      this.api.post('/oauth/token')
        .then(res => {
          resolve(res)
        })
        .catch(err => {
          console.error(err)
          reject(err)
        })
    })
  }
}
