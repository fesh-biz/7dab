import AppModel from 'src/models/app-model'

export default class MyVote extends AppModel {
  static entity = 'my_votes'

  static fields () {
    return {
      id: this.attr(null),
      user_id: this.attr(null),
      ratingable_id: this.attr(null),
      ratingable_type_name: this.attr(null),
      is_positive: this.boolean(false)
    }
  }
}
