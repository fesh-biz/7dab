import AppModel from 'src/models/AppModel'

export default class User extends AppModel {
  static entity = 'users'

  static fields () {
    return {
      id: this.attr(null),
      first_name: this.attr(''),
      last_name: this.attr('')
    }
  }
}
