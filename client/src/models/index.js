import VuexORM from '@vuex-orm/core'
const database = new VuexORM.Database()

import PostModel from 'src/models/content/PostModel'
import PostTagModel from 'src/models/content/PostTagModel'
import TagModel from 'src/models/content/TagModel'
import Me from 'src/models/user/Me'
import User from 'src/models/user/User'

database.register(PostModel)
database.register(PostTagModel)
database.register(TagModel)
database.register(Me)
database.register(User)

export default database
