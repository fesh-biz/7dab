import Api from 'src/plugins/api/api'

export default class Search {
  constructor () {
    if (Search.instance) {
      return Search.instance
    }

    this.api = new Api()

    Search.instance = this
  }

  search (data) {
    return new Promise((resolve, reject) => {
      this.api.get('content/search', data)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }
}
