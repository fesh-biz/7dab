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
import Comment from 'src/models/content/comment'
import Page from 'src/models/cache/page'
import CachePost from 'src/models/cache/cache-post'
import CacheComment from 'src/models/cache/cache-comment'
import CachePostImage from 'src/models/cache/cache-post-image'
import CachePostText from 'src/models/cache/cache-post-text'
import CacheRating from 'src/models/cache/cache-rating'
import CacheTag from 'src/models/cache/cache-tag'
import CacheUser from 'src/models/cache/cache-user'

database.register(CacheUser)
database.register(CacheTag)
database.register(CacheRating)
database.register(CachePostText)
database.register(CachePostImage)
database.register(CacheComment)
database.register(CachePost)
database.register(Page)
database.register(Comment)
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
