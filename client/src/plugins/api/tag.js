import Api from 'src/plugins/api/api'

export default class Tag {
  constructor () {
    if (Tag.instance) {
      return Tag.instance
    }

    this.api = new Api()

    Tag.instance = this
  }

  search (name) {
    return new Promise((resolve, reject) => {
      this.api.get(`/tags/search?title=${name}`)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  update (id, data) {
    return new Promise((resolve, reject) => {
      this.api.put(`admin/tags/${id}`, data)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }
}
