import AppModel from 'src/models/app-model'

export default class CommentAuthor extends AppModel {
  static entity = 'comment_authors'

  static fields () {
    return {
      id: this.attr(null),
      login: this.string('')
    }
  }
}
