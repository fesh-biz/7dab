import AppModel from 'src/models/AppModel'

export default class PostImageModel extends AppModel {
  static entity = 'post_images'

  static fields () {
    return {
      id: this.attr(null),
      post_id: this.number(0),
      order: this.number(0),
      original_filename: this.string(''),
      title: this.attr(null),
      recognized_text: this.string(''),
      original_file_path: this.attr(null),
      desktop_file_path: this.attr(null),
      mobile_file_path: this.attr(null),
      data: this.attr(null),
      created_at: this.string(''),
      updated_at: this.string(''),
      type: this.string('')
    }
  }

  get imageData () {
    return JSON.parse(this.data)
  }
}
