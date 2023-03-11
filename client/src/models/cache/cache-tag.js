import AppModel from 'src/models/app-model'
import Tag from 'src/models/content/tag'
import Page from 'src/models/cache/page'

export default class CacheTag extends AppModel {
  static entity = 'cache_tags'

  static fields () {
    return {
      id: this.attr(null),

      entity_id: this.attr(null),
      entity: this.belongsTo(Tag, 'entity_id', 'id'),

      page_id: this.attr(null),
      page: this.belongsTo(Page, 'page_id', 'id')
    }
  }
}
