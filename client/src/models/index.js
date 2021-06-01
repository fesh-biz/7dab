import VuexORM from '@vuex-orm/core'
const database = new VuexORM.Database()

import Me from 'src/models/user/Me'
import User from 'src/models/user/User'

database.register(Me)
database.register(User)

export default database
