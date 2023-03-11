import AppModel from 'src/models/app-model'
import Comment from 'src/models/content/comment'
import Page from 'src/models/cache/page'

export default class CacheComment extends AppModel {
  static entity = 'cache_comments'

  static fields () {
    return {
      id: this.attr(null),

      entity_id: this.attr(null),
      entity: this.belongsTo(Comment, 'entity_id', 'id'),

      page_id: this.attr(null),
      page: this.belongsTo(Page, 'page_id', 'id')
    }
  }
}
