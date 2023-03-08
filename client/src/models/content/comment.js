import AppModel from 'src/models/app-model'
import Rating from 'src/models/rating/rating'
import MyVote from 'src/models/rating/my-vote'
import User from 'src/models/user/user'

export default class Comment extends AppModel {
  static entity = 'comments'

  static fields () {
    return {
      id: this.attr(null),
      body: this.string(''),
      created_at: this.attr(null),

      commentable_id: this.attr(null),
      commentable_type_name: this.attr(null),
      answers: this.morphMany(Comment, 'commentable_id', 'commentable_type_name'),

      rating: this.morphOne(Rating, 'ratingable_id', 'ratingable_type_name'),
      my_vote: this.morphOne(MyVote, 'ratingable_id', 'ratingable_type_name'),

      user_id: this.attr(null),
      user: this.belongsTo(User, 'user_id', 'id')
    }
  }
}
