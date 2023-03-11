import AppModel from 'src/models/app-model'
import PostText from 'src/models/content/post-text'
import Page from 'src/models/cache/page'

export default class CachePostText extends AppModel {
  static entity = 'cache_post_texts'

  static fields () {
    return {
      id: this.attr(null),

      entity_id: this.attr(null),
      entity: this.belongsTo(PostText, 'entity_id', 'id'),

      page_id: this.attr(null),
      page: this.belongsTo(Page, 'page_id', 'id')
    }
  }
}
