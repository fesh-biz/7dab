import AppModel from 'src/models/app-model'

export default class PostYouTube extends AppModel {
  static entity = 'post_you_tubes'

  static fields () {
    return {
      id: this.attr(null),
      type: this.string('youtube'),
      post_id: this.attr(null),
      order: this.number(0),
      title: this.string(''),
      youtube_id: this.string('')
    }
  }
}
