import _ from 'lodash'

export default class Search {
  constructor (formModel) {
    if (Search.instance) {
      return Search.instance
    }

    this.formModel = _.cloneDeep(formModel)

    Search.instance = this
  }
}
