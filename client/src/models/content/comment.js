import AppModel from 'src/models/app-model'
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

      user_id: this.attr(null),
      author: this.belongsTo(User, 'user_id', 'id')
    }
  }
}
