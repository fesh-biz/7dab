import Api from 'src/plugins/api/api'

export default class Tag {
  constructor () {
    if (Tag.instance) {
      return Tag.instance
    }

    this.api = new Api()

    Tag.instance = this
  }

  fetchByIds (ids) {
    let query = ''

    ids.forEach((id, i) => {
      query += `tids[]=${id}`
      if (i + 1 < ids.length) {
        query += '&'
      }
    })

    return new Promise((resolve, reject) => {
      this.api.get(`/tags/search?${query}`, 'tags')
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  search (name, limit = 5) {
    return new Promise((resolve, reject) => {
      this.api.get(`/tags/search?title=${name}&limit=${limit}`, null, 'tags')
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
