import AppModel from 'src/models/app-model'
import PostImage from 'src/models/content/post-image'
import Page from 'src/models/cache/page'

export default class CachePostImage extends AppModel {
  static entity = 'cache_post_images'

  static fields () {
    return {
      id: this.attr(null),

      entity_id: this.attr(null),
      entity: this.belongsTo(PostImage, 'entity_id', 'id'),

      page_id: this.attr(null),
      page: this.belongsTo(Page, 'page_id', 'id')
    }
  }
}
