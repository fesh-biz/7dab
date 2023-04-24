import AppModel from 'src/models/app-model'

export default class User extends AppModel {
  static entity = 'users'

  static fields () {
    return {
      id: this.attr(null),
      login: this.string(''),
      avatar: this.attr(null)
    }
  }
}
