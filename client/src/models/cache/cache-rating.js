import AppModel from 'src/models/app-model'
import Rating from 'src/models/rating/rating'
import Page from 'src/models/cache/page'

export default class CacheRating extends AppModel {
  static entity = 'cache_ratings'

  static fields () {
    return {
      id: this.attr(null),

      rating: this.belongsTo(Rating, 'id', 'id'),

      page_id: this.attr(null),
      page: this.belongsTo(Page, 'page_id', 'id')
    }
  }
}
