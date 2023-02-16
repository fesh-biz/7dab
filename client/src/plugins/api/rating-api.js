import Api from 'src/plugins/api/api'

export default class RatingApi {
  constructor () {
    if (RatingApi.instance) {
      return RatingApi.instance
    }

    this.api = new Api()

    RatingApi.instance = this
  }

  vote (type, id, isUpvote) {
    return new Promise((resolve, reject) => {
      this.api.post('/ratings/vote', {
        id: id,
        type: type,
        is_upvote: isUpvote
      })
        .then(res => resolve(res))
        .catch(err => resolve(err))
    })
  }
}
