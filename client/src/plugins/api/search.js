import Api from 'src/plugins/api/api'

export default class Search {
  constructor () {
    if (Search.instance) {
      return Search.instance
    }

    this.api = new Api()

    Search.instance = this
  }

  search (data, ormTableName) {
    return new Promise((resolve, reject) => {
      this.api.get('search/posts', data, ormTableName)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }
}
