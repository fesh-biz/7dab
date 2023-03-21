import _ from 'lodash'

import AppModel from 'src/models/app-model'
import User from 'src/models/user/user'
import Tag from 'src/models/content/tag'
import PostTag from 'src/models/content/post-tag'
import PostText from 'src/models/content/post-text'
import PostImage from 'src/models/content/post-image'
import Rating from 'src/models/rating/rating'
import MyVote from 'src/models/rating/my-vote'
import PostYouTube from 'src/models/content/post-you-tube'

export default class Post extends AppModel {
  static entity = 'posts'

  static fields () {
    return {
      id: this.attr(null),

      user_id: this.attr(null),
      user: this.belongsTo(User, 'user_id'),

      title: this.string(''),
      status: this.string(''),

      is_expanded: this.boolean(false),
      is_images_loaded: this.boolean(false),

      views: this.number(0),
      comments: this.number(0),

      rating: this.morphOne(Rating, 'ratingable_id', 'ratingable_type_name'),
      my_vote: this.morphOne(MyVote, 'ratingable_id', 'ratingable_type_name'),

      tags: this.belongsToMany(Tag, PostTag, 'post_id', 'tag_id'),
      post_texts: this.hasMany(PostText, 'post_id'),
      post_images: this.hasMany(PostImage, 'post_id'),
      post_you_tubes: this.hasMany(PostYouTube, 'post_id')
    }
  }

  get content () {
    const content = this.post_texts.concat(this.post_images).concat(this.post_you_tubes)

    return _.orderBy(content, 'order')
  }

  get isDraft () {
    return this.status === 'draft'
  }

  get isPending () {
    return this.status === 'pending'
  }

  get isReviewing () {
    return this.status === 'reviewing'
  }

  get isApproved () {
    return this.status === 'approved'
  }

  get isDeclined () {
    return this.status === 'declined'
  }

  get isEditing () {
    return this.status === 'editing'
  }
}
