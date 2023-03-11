import AppModel from 'src/models/app-model'
import MyVote from 'src/models/rating/my-vote'
import Page from 'src/models/cache/page'

export default class CacheMyVote extends AppModel {
  static entity = 'cache_my_votes'

  static fields () {
    return {
      id: this.attr(null),

      my_vote_id: this.attr(null),
      myVote: this.belongsTo(MyVote, 'my_vote_id', 'id'),

      page_id: this.attr(null),
      page: this.belongsTo(Page, 'page_id', 'id')
    }
  }
}
