import AppModel from 'src/models/AppModel'

export default class PostImageModel extends AppModel {
  static entity = 'post_images'

  static fields () {
    return {
      id: this.attr(null),
      type: this.string(''),
      post_id: this.attr(null),
      order: this.number(0),
      original_name: this.string(''),
      title: this.attr(null),
      recognized_text: this.string(''),
      filename: this.string(''),
      width: this.number(0),
      height: this.number(0),
      size_kb: this.number(0)
    }
  }
}
