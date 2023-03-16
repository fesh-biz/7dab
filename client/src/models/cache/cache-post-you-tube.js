import AppModel from 'src/models/app-model'
import PostYouTube from 'src/models/content/post-you-tube'
import Page from 'src/models/cache/page'

export default class CachePostYouTube extends AppModel {
  static entity = 'cache_post_you_tubes'

  static fields () {
    return {
      id: this.attr(null),

      entity_id: this.attr(null),
      entity: this.belongsTo(PostYouTube, 'entity_id', 'id'),

      page_id: this.attr(null),
      page: this.belongsTo(Page, 'page_id', 'id')
    }
  }
}
