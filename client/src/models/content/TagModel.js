import AppModel from 'src/models/AppModel'

export default class TagModel extends AppModel {
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
