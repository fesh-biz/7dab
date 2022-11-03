import AppModel from 'src/models/app-model'

export default class Tag extends AppModel {
  static entity = 'tags'

  static fields () {
    return {
      id: this.attr(null),
      title: this.string(''),
      slug: this.string(''),
      body: this.string('')
    }
  }
}
