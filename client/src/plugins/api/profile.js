import Api from 'src/plugins/api/api'

export default class Profile {
  constructor () {
    if (Profile.instance) {
      return Profile.instance
    }

    this.api = new Api()

    Profile.instance = this
  }

  fetchContentStats () {
    return new Promise((resolve, reject) => {
      this.api.get('profile/content-stats', null, null, true)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  uploadAvatar (avatar) {
    const formData = new FormData()
    formData.append('avatar', avatar)

    return new Promise((resolve, reject) => {
      this.api.post('profile/avatar', formData, null, null, true)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }
}
