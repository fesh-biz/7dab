import _ from 'lodash'

export default class Cache {
  constructor () {
    if (Cache.instance) {
      return Cache.instance
    }

    this.pages = {}

    Cache.instance = this
  }

  getCurrentPageEntitiesIds (type) {
    if (!this.getPage()) {
      throw new Error('Cage for given page ' + this.getPageName() + ' not found.')
    }

    const modelName = this.getModelName(type)

    return this.getPage()[modelName]
  }

  hasCurrentPage () {
    return !!this.pages[this.getPageName()]
  }

  getPageName () {
    return window.location.href.split(window.location.host).pop() || 'home'
  }

  getPage () {
    if (!this.pages[this.getPageName()]) {
      this.pages[this.getPageName()] = {}
    }

    return this.pages[this.getPageName()]
  }

  setPageCache (data, type) {
    if (data.data) {
      return this.setPageCache(data.data, type)
    }

    const page = this.getPage()
    const currentIds = this.getDataIds(data, type)

    for (const modelName in currentIds) {
      if (!page?.[modelName]) {
        page[modelName] = []
      }

      const ids = currentIds[modelName]

      for (const id of ids) {
        if (!page[modelName].includes(id)) {
          page[modelName].push(id)
        }
      }
    }

    this.refreshPages()
    console.log('Cache', this.pages)
  }

  refreshPages () {
    this.pages = _.cloneDeep(this.pages)
  }

  getDataIds (data, type, ids = {}) {
    const modelName = this.getModelName(type)
    if (!ids[modelName]) {
      ids[modelName] = []
    }

    const handleArray = (data) => {
      for (const entity of data) {
        if (entity.id) {
          handleObject(entity)
        }
      }
    }

    const handleObject = (entity) => {
      if (entity?.id) {
        if (!ids[modelName].includes(entity.id)) {
          ids[modelName].push(entity.id)
        }

        for (const prop in entity) {
          if (!entity.hasOwnProperty(prop)) continue
          const value = entity[prop]

          if (Array.isArray(value)) {
            this.getDataIds(value, prop, ids)
          }

          if (value?.id) {
            this.getDataIds(value, prop, ids)
          }
        }
      }
    }

    if (Array.isArray(data)) {
      handleArray(data)
    }

    if (data?.id) {
      handleObject(data)
    }

    return ids
  }

  getModelName (type) {
    const models = {
      post: ['posts'],
      postImage: ['post_images'],
      tag: ['tags'],
      postText: ['post_texts'],
      user: ['user'],
      rating: ['rating'],
      myVote: ['my_vote', 'my_votes'],
      comment: ['comments', 'answers']
    }

    for (const name in models) {
      if (!models.hasOwnProperty(name)) continue

      if (models[name].includes(type)) {
        return name
      }
    }

    throw new Error(`Model name for given type ${type} not found`)
  }
}
