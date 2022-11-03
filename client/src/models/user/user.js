import AppModel from 'src/models/app-model'

export default class User extends AppModel {
  static entity = 'users'

  static fields () {
    return {
      id: this.attr(null),
      name: this.string(''),
      rating: this.number(0)
    }
  }
}
