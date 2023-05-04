import AppModel from 'src/models/app-model'

export default class Me extends AppModel {
  static entity = 'me'

  static fields () {
    return {
      id: this.uid(null),
      login: this.string(''),
      is_verified: this.boolean(false),
      role_id: this.number(0),
      avatar: this.attr(null)
    }
  }
}
