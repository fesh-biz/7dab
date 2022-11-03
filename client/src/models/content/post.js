import _ from 'lodash'

import AppModel from 'src/models/app-model'
import User from 'src/models/user/user'
import Tag from 'src/models/content/tag'
import PostTag from 'src/models/content/post-tag'
import PostText from 'src/models/content/post-text'
import PostImage from 'src/models/content/post-image'
import PostStat from 'src/models/content/post-stat'

export default class Post extends AppModel {
  static entity = 'posts'

  static fields () {
    return {
      id: this.attr(null),

      user_id: this.attr(null),
      user: this.belongsTo(User, 'user_id'),

      title: this.string(''),
      slug: this.string(''),
      status: this.string(''),

      tags: this.belongsToMany(Tag, PostTag, 'post_id', 'tag_id'),
      post_texts: this.hasMany(PostText, 'post_id'),
      post_images: this.hasMany(PostImage, 'post_id'),
      post_stat: this.hasOne(PostStat, 'post_id')
    }
  }

  get content () {
    const content = this.post_texts.concat(this.post_images)

    return _.orderBy(content, 'order')
  }
}
