import Validator from 'src/plugins/tools/validator'
import _ from 'lodash'

export default class Post {
  constructor () {
    if (Post.instance) {
      return Post.instance
    }

    this.title = ''
    this.sections = []

    this.validator = new Validator({
      title: '',
      data: []
    })

    Post.instance = this
  }

  addSection () {
    const nextOrder = !this.sections.length ? 1 : this.sections[this.sections.length - 1].order + 1
    this.sections.push({
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
    this.sections.splice(sectionIndex, 1)
  }
}
