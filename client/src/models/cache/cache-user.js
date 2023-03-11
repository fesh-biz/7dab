import AppModel from 'src/models/app-model'
import User from 'src/models/user/user'
import Page from 'src/models/cache/page'

export default class CacheUser extends AppModel {
  static entity = 'cache_users'

  static fields () {
    return {
      id: this.attr(null),

      entity_id: this.attr(null),
      entity: this.belongsTo(User, 'entity_id', 'id'),

      page_id: this.attr(null),
      page: this.belongsTo(Page, 'page_id', 'id')
    }
  }
}
