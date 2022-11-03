import AppModel from 'src/models/app-model'

export default class Me extends AppModel {
  static entity = 'me'

  static fields () {
    return {
      id: this.uid(null),
      name: this.string(''),
      email: this.string('')
    }
  }
}
