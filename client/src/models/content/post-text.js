import AppModel from 'src/models/app-model'

export default class PostText extends AppModel {
  static entity = 'post_texts'

  static fields () {
    return {
      id: this.attr(null),
      type: this.string('text'),
      post_id: this.attr(null),
      order: this.number(0),
      body: this.string('')
    }
  }
}
