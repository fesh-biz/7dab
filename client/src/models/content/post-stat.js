import { Model } from '@vuex-orm/core'

export default class PostStat extends Model {
  static entity = 'post_stats'

  static fields () {
    return {
      id: this.attr(null),

      post_id: this.number(0),
      views: this.number(0),
      positive_votes: this.number(0),
      negative_votes: this.number(0),
      comments: this.number(0),

      created_at: this.attr(''),
      updated_at: this.attr('')
    }
  }

  get rating () {
    return this.positive_votes - this.negative_votes
  }
}
