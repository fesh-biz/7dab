import AppModel from 'src/models/AppModel'

export default class Post extends AppModel {
  static entity = 'posts'

  static fields () {
    return {
      id: this.attr(null),
      user_id: this.attr(null),
      title: this.string(''),
      body: this.string(''),
      rating: this.number(0),
      slug: this.string(''),
      is_approved: this.boolean(false),
      total_views: this.number(0)
    }
  }
}
