import AppModel from 'src/models/app-model'
import CachePost from 'src/models/cache/cache-post'

export default class Page extends AppModel {
  static entity = 'pages'

  static fields () {
    return {
      id: this.attr(null),
      cache_posts: this.hasMany(CachePost, 'page_id', 'id')
    }
  }
}
