// import _ from 'lodash'

export default class Search {
  constructor () {
    if (Search.instance) {
      return Search.instance
    }

    this.requests = []
    this.postIds = []

    Search.instance = this
  }
}
