import VuexORM from '@vuex-orm/core'

const database = new VuexORM.Database()

import Post from 'src/models/content/post'
import PostTag from 'src/models/content/post-tag'
import Tag from 'src/models/content/tag'
import Me from 'src/models/user/me'
import User from 'src/models/user/user'
import PostImage from 'src/models/content/post-image'
import PostText from 'src/models/content/post-text'
import Rating from 'src/models/rating/rating'
import MyVote from 'src/models/rating/my-vote'

database.register(MyVote)
database.register(Rating)
database.register(PostImage)
database.register(Post)
database.register(PostTag)
database.register(PostText)
database.register(Tag)
database.register(Me)
database.register(User)

export default database
