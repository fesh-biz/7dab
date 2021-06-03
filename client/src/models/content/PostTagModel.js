import AppModel from 'src/models/AppModel'

export default class PostTagModel extends AppModel {
  static entity = 'post_tag'

  static primaryKey = ['post_id', 'tag_id']

  static fields () {
    return {
      post_id: this.attr(null),
      tag_id: this.attr(null)
    }
  }
}
