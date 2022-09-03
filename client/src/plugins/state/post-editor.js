import Validator from 'src/plugins/Validator'
import _ from 'lodash'

export default class PostEditor {
  constructor () {
    if (PostEditor.instance) {
      return PostEditor.instance
    }

    this.title = ''
    this.sections = []

    this.validator = new Validator({
      title: '',
      data: []
    })

    PostEditor.instance = this
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
