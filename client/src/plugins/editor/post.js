import Validator from 'src/plugins/tools/validator'
import PostApi from 'src/plugins/api/post'
import _ from 'lodash'

const formModel = {
  title: '',
  sections: []
}

export default class Post {
  constructor () {
    if (Post.instance) {
      return Post.instance
    }

    this.formModel = formModel
    this.api = new PostApi()

    this.validator = new Validator(formModel)

    Post.instance = this
  }

  addSection () {
    const nextOrder = !this.formModel.sections.length ? 1 : this.formModel.sections[this.formModel.sections.length - 1].order + 1
    this.formModel.sections.push({
      order: nextOrder,
      type: 'text',
      content: ''
    })
  }

  updateSection (sectionOrder, content) {
    const section = _.find(this.sections, { order: sectionOrder })
    section.content = content
  }

  deleteSection (sectionIndex) {
    this.formModel.sections.splice(sectionIndex, 1)
  }

  saveOrUpdate () {
    return new Promise((resolve, reject) => {
      this.api.store('/content/posts', this.formModel)
        .then(res => resolve(res))
        .catch(res => {
          console.log('err.response', res.response)
          this.validator.setErrors(res)
          reject(res)
        })
    })
  }
}
