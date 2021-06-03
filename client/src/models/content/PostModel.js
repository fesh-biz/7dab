import AppModel from 'src/models/AppModel'
import User from 'src/models/user/User'
import TagModel from 'src/models/content/TagModel'
import PostTagModel from 'src/models/content/PostTagModel'

export default class PostModel extends AppModel {
  static entity = 'posts'

  static fields () {
    return {
      id: this.attr(null),

      user_id: this.attr(null),
      user: this.belongsTo(User, 'user_id'),

      title: this.string(''),
      body: this.string(''),
      rating: this.number(0),
      slug: this.string(''),
      is_approved: this.boolean(false),
      total_views: this.number(0),
      total_comments: this.number(0),

      tags: this.belongsToMany(TagModel, PostTagModel, 'post_id', 'tag_id')
    }
  }
}
