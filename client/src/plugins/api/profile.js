import Api from 'src/plugins/api/api'

export default class ProfileApi {
  constructor () {
    if (ProfileApi.instance) {
      return ProfileApi.instance
    }

    this.api = new Api()

    ProfileApi.instance = this
  }

  fetchContentStats () {
    return new Promise((resolve, reject) => {
      this.api.get('profile/content-stats', null, null, true)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }
}
