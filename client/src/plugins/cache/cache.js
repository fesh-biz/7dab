import Page from 'src/models/cache/page'
import CachePost from 'src/models/cache/cache-post'
import CachePostImage from 'src/models/cache/cache-post-image'
import CacheTag from 'src/models/cache/cache-tag'
import CachePostText from 'src/models/cache/cache-post-text'
import CacheUser from 'src/models/cache/cache-user'
import CacheRating from 'src/models/cache/cache-rating'
import CacheMyVote from 'src/plugins/api/my-vote'
import CacheComment from 'src/models/cache/cache-comment'
import CachePostYouTube from 'src/models/cache/cache-post-you-tube'

export default class Cache {
  constructor () {
    if (Cache.instance) {
      return Cache.instance
    }

    Cache.instance = this
  }

  getPagePath () {
    return window.location.href
  }

  async getOrCreatePage () {
    if (!Page.query().where('path', this.getPagePath()).first()) {
      await Page.insert({
        data: { path: this.getPagePath() }
      })
    }

    return this.getCurrentPage()
  }

  async getPagination (tableName) {
    const page = await this.getOrCreatePage()

    if (!page.pagination[tableName]) {
      throw new Error('Pagination for given table name ' + tableName + ' not found')
    }

    return page.pagination[tableName]
  }

  getCurrentPageCacheIds (tableName, ids) {
    const page = this.getCurrentPage()
    if (!page || !this.hasCacheForCurrentPage(tableName)) {
      return []
    }

    const model = this.getModelByTableName(tableName)

    let res = model.query().where('page_id', page.id)

    if (ids) {
      res = res.where(entity => ids.includes(entity.entity_id)).get()
    } else {
      res = res.get()
    }

    return res.map(r => r.entity_id)
  }

  getEntityCache (tableName, id) {
    const model = this.getModelByTableName(tableName)
    const page = this.getCurrentPage()
    if (!page) return null

    return model.query().where('entity_id', id)
      .where('page_id', page.id)
      .first()
  }

  async getOrCreateEntityCache (tableName, entityId) {
    let entity = this.getEntityCache(tableName, entityId)

    if (!entity) {
      const page = await this.getOrCreatePage()
      const model = this.getModelByTableName(tableName)

      entity = await model.insert({
        data: {
          entity_id: entityId,
          page_id: page.id
        }
      })
    }

    return entity
  }

  async updateEntityCache (tableName, id, data) {
    const model = this.getModelByTableName(tableName)
    const page = this.getCurrentPage()

    const res = await model.update({
      where: e => e.entity_id === id && e.page_id === page.id,
      data: data
    })

    return res[0]
  }

  async insertEntityCache (tableName, id, data = {}) {
    const model = this.getModelByTableName(tableName)
    const page = this.getOrCreatePage()
    data = Object.assign(data, {
      page_id: page.id,
      entity_id: id
    })

    await model.insert({
      data: data
    })
  }

  getAll () {
    return Page.query().withAll().get()
  }

  getCurrentPage () {
    const page = Page.query().where('path', this.getPagePath()).first()

    if (!page) {
      return null
    }

    return page
  }

  async updatePagePagination (meta, tableName) {
    const page = await this.getOrCreatePage()

    if (!page.pagination[tableName]) {
      throw new Error('Can not set pagination for given table name ' + tableName)
    }

    await Page.update({
      where: p => p.path === this.getPagePath(),
      data: {
        pagination: {
          [tableName]: {
            is_last: meta.is_last,
            page: meta.current_page + 1
          }
        }
      }
    })
  }

  getModelByTableName (tableName) {
    if (!tableName) {
      throw new Error('Property tableName is required')
    }

    const modelName = this.getModelName(tableName)
    return this.getModel(modelName)
  }

  hasCacheForCurrentPage (tableName) {
    const model = this.getModelByTableName(tableName)

    const page = this.getCurrentPage()
    if (!page) {
      return false
    }

    return !!model.query().where('page_id', page.id).count()
  }

  async setPageCache (data, type) {
    if (data?.data?.meta) {
      await this.updatePagePagination(data.data.meta, type)
    }

    if (data.data) {
      return this.setPageCache(data.data, type)
    }

    const page = await this.getOrCreatePage()
    const currentIds = this.getDataIds(data, type)

    for (const modelName in currentIds) {
      const ids = currentIds[modelName]
      const Model = this.getModel(modelName)

      const entries = []
      for (const id of ids) {
        const isExists = Model.query()
          .where(e => e.entity_id === id && e.page_id === page.id)
          .first()

        if (!isExists) {
          entries.push({
            entity_id: id,
            page_id: page.id
          })
        }
      }

      await Model.insert({
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
      comment: CacheComment,
      postYouTube: CachePostYouTube
    }

    if (models[modelName]) {
      return models[modelName]
    }

    throw new Error(`Model for given model name ${modelName} not found`)
  }

  getModelName (tableName) {
    const modelNameMap = {
      post: ['posts'],
      postImage: ['post_images'],
      tag: ['tags'],
      postText: ['post_texts'],
      user: ['user', 'users'],
      rating: ['rating'],
      myVote: ['my_vote', 'my_votes'],
      comment: ['comments', 'answers'],
      postYouTube: ['post_you_tubes']
    }

    for (const name in modelNameMap) {
      if (modelNameMap[name].includes(tableName)) {
        return name
      }
    }

    throw new Error(`Model name for given table name ${tableName} not found`)
  }
}
