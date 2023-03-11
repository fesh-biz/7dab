import AppModel from 'src/models/app-model'
import CachePost from 'src/models/cache/cache-post'
import CacheComment from 'src/models/cache/cache-comment'

export default class Page extends AppModel {
  static entity = 'pages'

  static fields () {
    return {
      id: this.attr(null),

      isLastFetched: this.boolean(false),
      currentPage: this.number(0),
      path: this.string(''),

      posts: this.hasMany(CachePost, 'page_id', 'id'),
      comments: this.hasMany(CacheComment, 'page_id', 'id')
    }
  }
}
