import Api from 'src/plugins/api/api'

export default class UserApi {
  constructor () {
    if (UserApi.instance) {
      return UserApi.instance
    }

    this.api = new Api()

    UserApi.instance = this
  }

  fetchStats (id) {
    return new Promise((resolve, reject) => {
      this.api.get(`/users/${id}/stats`, null, null, true)
        .then(res => resolve(res))
        .catch(err => {
          console.log(err)
          reject(err)
        })
    })
  }

  fetchMe () {
    return new Promise((resolve, reject) => {
      this.api.get(
        '/me',
        null,
        null,
        true
      )
        .then(res => resolve(res))
        .catch(err => {
          console.error(err)
          reject(reject)
        })
    })
  }

  forgotPassword (email) {
    return new Promise((resolve, reject) => {
      this.api.post(
        'password-forgot',
        {
          email: email
        },
        null,
        null,
        true
      )
        .then(res => {
          resolve(res)
        })
        .catch(err => {
          console.error(err)
          reject(err)
        })
    })
  }

  logout () {
    return new Promise((resolve, reject) => {
      this.api.post(
        '/logout',
        null,
        null,
        null,
        true
      )
        .then(res => {
          resolve(res)
        })
        .catch(err => {
          console.error(err)
          reject(err)
        })
    })
  }

  passwordReset (token, password, passwordConfirmation) {
    return new Promise((resolve, reject) => {
      this.api.post(
        'password-reset',
        {
          token: token,
          password: password,
          password_confirmation: passwordConfirmation
        },
        null,
        null,
        true
      )
        .then(res => {
          resolve(res)
        })
        .catch(err => {
          console.error(err)
          reject(err)
        })
    })
  }

  register (data) {
    return new Promise((resolve, reject) => {
      this.api.post(
        'register',
        data,
        null,
        null,
        true
      )
        .then(res => {
          resolve(res)
        })
        .catch(err => {
          console.error(err)
          reject(err)
        })
    })
  }

  verifyEmail (token) {
    return new Promise((resolve, reject) => {
      this.api.post(
        'verify-email',
        { token: token },
        null,
        null,
        true
      )
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  fetchUser (id) {
    return new Promise((resolve, reject) => {
      this.api.get(`/users/${id}`, null, 'users')
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }
}
