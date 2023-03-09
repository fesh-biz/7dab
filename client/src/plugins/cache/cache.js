import _ from 'lodash'
import Post from 'src/models/content/post'
import PostImage from 'src/models/content/post-image'
import Tag from 'src/models/content/tag'
import PostText from 'src/models/content/post-text'
import User from 'src/models/user/user'
import Rating from 'src/models/rating/rating'
import MyVote from 'src/models/rating/my-vote'
import Comment from 'src/models/content/comment'

export default class Cache {
  constructor () {
    if (Cache.instance) {
      return Cache.instance
    }

    this.pages = {}

    Cache.instance = this
  }

  getIsLastFetched () {
    return this.getPage().isLastFetched
  }

  setIsLastFetched (value) {
    this.getPage().isLastFetched = value
  }

  getPageIds (type) {
    if (!this.getPage()) {
      throw new Error('Cage for given page ' + this.getPageName() + ' not found.')
    }

    const modelName = this.getModelName(type)

    return this.getPage()[modelName]
  }

  hasCurrentPage () {
    return !!this.pages[this.getPageName()]
  }

  getTotalIds () {
    let total = 0

    for (const pageName in this.pages) {
      for (const entityName in this.pages[pageName]) {
        if (Array.isArray(this.pages[pageName][entityName])) {
          total += this.pages[pageName][entityName].length
        }
      }
    }

    return total
  }

  getPageName () {
    return window.location.href.split(window.location.host).pop() || 'home'
  }

  getPage () {
    if (!this.pages[this.getPageName()]) {
      this.pages[this.getPageName()] = {
        isLastFetched: false,
        currentPage: 0
      }
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

    this.refreshCache()
    console.log('Cache', this.pages)
    console.log('Total IDS', this.getTotalIds())
  }

  refreshCache () {
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
    const modelMap = {
      post: ['posts'],
      postImage: ['post_images'],
      tag: ['tags'],
      postText: ['post_texts'],
      user: ['user'],
      rating: ['rating'],
      myVote: ['my_vote', 'my_votes'],
      comment: ['comments', 'answers']
    }

    const models = {
      post: Post,
      postImage: PostImage,
      tag: Tag,
      postText: PostText,
      user: User,
      rating: Rating,
      myVote: MyVote,
      comment: Comment
    }

    for (const name in modelMap) {
      if (modelMap[name].includes(type) && models[name]) {
        return models[name]
      }
    }

    throw new Error(`Model name for given type ${type} not found`)
  }
}
