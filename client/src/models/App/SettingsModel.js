import AppModel from 'src/models/AppModel'

export default class SettingsModel extends AppModel {
  static entity = 'settings'

  static fields () {
    return {
      id: this.attr(null),
      feed_offset: this.number(0),
      feed_entries_per_request: this.number(10)
    }
  }
}
