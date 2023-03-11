import AppModel from 'src/models/app-model'
import Post from 'src/models/content/post'
import Page from 'src/models/cache/page'

export default class CachePost extends AppModel {
  static entity = 'cache_posts'

  static fields () {
    return {
      id: this.attr(null),

      entity_id: this.attr(null),
      entity: this.belongsTo(Post, 'entity_id', 'id'),

      page_id: this.attr(null),
      page: this.belongsTo(Page, 'page_id', 'id'),

      is_expanded: this.boolean(false),
      is_images_loaded: this.boolean(false)
    }
  }
}
