import AppModel from 'src/models/app-model'

export default class Rating extends AppModel {
  static entity = 'ratings'

  static fields () {
    return {
      id: this.attr(null),
      ratingable_id: this.attr(null),
      ratingable_type_name: this.attr(null),
      positive_votes: this.number(0),
      negative_votes: this.number(0)
    }
  }
}
