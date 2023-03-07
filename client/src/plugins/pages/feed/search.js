import _ from 'lodash'

export default class Search {
  constructor () {
    if (Search.instance) {
      return Search.instance
    }

    this.responsePostIds = []

    Search.instance = this
  }

  addPostIdsByRequestData (requestData, posts) {
    const res = this.getResponse(requestData)
    if (!res) {
      this.responsePostIds.push({
        requestData: requestData,
        ids: posts.map(post => post.id)
      })

      return
    }

    posts.forEach(post => {
      console.log('pushing')
      res.ids.push(post.id)
    })
  }

  getResponse (requestData) {
    for (const response of this.responsePostIds) {
      if (_.isEqual(requestData, response.requestData)) {
        return response
      }
    }

    return null
  }

  getPostIdsByRequestData (requestData) {
    const res = this.getResponse(requestData)

    if (res) {
      return res.ids
    }

    return null
  }
}
