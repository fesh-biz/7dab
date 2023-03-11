import AppModel from 'src/models/app-model'

export default class Page extends AppModel {
  static entity = 'pages'

  static fields () {
    return {
      id: this.attr(null),

      path: this.string(''),

      pagination: this.attr({
        posts: {
          is_last: false,
          page: 1
        }
      })
    }
  }
}
