import _ from 'lodash'

export default class Search {
  constructor () {
    if (Search.instance) {
      return Search.instance
    }

    this.requests = []
    this.postIds = []

    Search.instance = this
  }

  getRequestIndex (queryVars) {
    let requestIndex = null
    for (const index in this.requests) {
      if (_.isEqual(queryVars, this.requests[index])) {
        requestIndex = index
        break
      }
    }

    return requestIndex
  }

  addRequest (queryVars) {
    if (this.getRequestIndex(queryVars) === null) {
      this.requests.push(queryVars)
    }
  }

  addPostIds (queryVars, postIds) {
    const requestIndex = this.getRequestIndex(queryVars)

    if (requestIndex === null) {
      throw new Error('Request with given queryVars not found')
    }

    const requestPostIds = this.postIds[requestIndex]
    if (!requestPostIds) {
      this.postIds.push(postIds)
    } else {
      postIds.forEach(id => {
        if (!requestPostIds.includes(id)) {
          requestPostIds.push(id)
        }
      })
    }
  }

  getPostIds (queryVars) {
    const requestIndex = this.getRequestIndex(queryVars)

    if (requestIndex !== null) {
      return this.postIds[requestIndex]
    }

    return null
  }
}
