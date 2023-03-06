export default class Search {
  constructor () {
    if (Search.instance) {
      return Search.instance
    }

    Search.instance = this
  }
}
