import Page from 'src/models/cache/page'
import CachePost from 'src/models/cache/cache-post'
import CachePostImage from 'src/models/cache/cache-post-image'
import CacheTag from 'src/models/cache/cache-tag'
import CachePostText from 'src/models/cache/cache-post-text'
import CacheUser from 'src/models/cache/cache-user'
import CacheRating from 'src/models/cache/cache-rating'
import CacheMyVote from 'src/plugins/api/my-vote'
import CacheComment from 'src/models/cache/cache-comment'

export default class Cache {
  constructor () {
    if (Cache.instance) {
      return Cache.instance
    }

    Cache.instance = this
  }

  getPagePath () {
    return window.location.href.split(window.location.host).pop() || 'home'
  }

  getPage () {
    if (!Page.query().where('path', this.getPagePath()).first()) {
      Page.insert({
        data: { path: this.getPagePath() }
      })
    }

    return Page.query().where('path', this.getPagePath()).first()
  }

  hasCacheForCurrentPage () {
    return !!Page.query().where('path', this.getPagePath()).first()
  }

  async setPageCache (data, type) {
    if (data.data) {
      return this.setPageCache(data.data, type)
    }

    const page = this.getPage()
    const currentIds = this.getDataIds(data, type)

    for (const modelName in currentIds) {
      const ids = currentIds[modelName]
      const model = this.getModel(modelName)

      const entries = []
      for (const id of ids) {
        entries.push({
          id: id,
          page_id: page.id
        })
      }

      await model.insert({
        data: entries
      })
    }
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

  getModel (modelName) {
    const models = {
      post: CachePost,
      postImage: CachePostImage,
      tag: CacheTag,
      postText: CachePostText,
      user: CacheUser,
      rating: CacheRating,
      myVote: CacheMyVote,
      comment: CacheComment
    }

    if (models[modelName]) {
      return models[modelName]
    }

    throw new Error(`Model for given model name ${modelName} not found`)
  }

  getModelName (type) {
    const modelNameMap = {
      post: ['posts'],
      postImage: ['post_images'],
      tag: ['tags'],
      postText: ['post_texts'],
      user: ['user'],
      rating: ['rating'],
      myVote: ['my_vote', 'my_votes'],
      comment: ['comments', 'answers']
    }

    for (const name in modelNameMap) {
      if (modelNameMap[name].includes(type)) {
        return name
      }
    }

    throw new Error(`Model name for given type ${type} not found`)
  }
}
