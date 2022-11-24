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

    this.formModel = _.cloneDeep(formModel)
    this.api = new PostApi()

    this.validator = new Validator(formModel)

    Post.instance = this
  }

  addSection (sectionType, content) {
    const nextOrder = !this.formModel.sections.length ? 1 : this.formModel.sections[this.formModel.sections.length - 1].order + 1
    this.formModel.sections.push({
      order: nextOrder,
      type: sectionType,
      content: content || null
    })
  }

  addSections (sectionType, offsetOrder, items) {
    items.forEach((item) => {
      if (item instanceof File) {
        this.addSection(sectionType, { file: item })
      }
    })
  }

  deleteSection (sectionIndex) {
    this.formModel.sections.splice(sectionIndex, 1)
  }

  updateSection (sectionOrder, content) {
    const section = _.find(this.formModel.sections, { order: sectionOrder })

    if (section.type === 'text') {
      section.content = content
      return
    }

    if (section.type === 'image') {
      section.content = section.content || {
        file: null,
        url: null,
        title: null
      }
      section.content = Object.assign(section.content, content)
    }
  }

  resetFormModel () {
    this.formModel = _.cloneDeep(formModel)
  }

  getFormData () {
    const model = this.formModel
    const formData = new FormData()
    formData.append('title', model.title)

    for (let i = 0; i < model.sections.length; i++) {
      const section = model.sections[i]

      if (section.type === 'image') {
        if (section.content.file) {
          formData.append(`sections[${i}][content][file]`, section.content.file)
        }

        formData.append(`sections[${i}][content][url]`, section.content.url || '')
        formData.append(`sections[${i}][content][title]`, section.content.title || '')
      }

      if (section.type === 'text') {
        formData.append(`sections[${i}][content]`, section.content || '')
      }

      formData.append(`sections[${i}][type]`, section.type)
      formData.append(`sections[${i}][order]`, section.order)
    }

    return formData
  }

  saveOrUpdate () {
    return new Promise((resolve, reject) => {
      this.api.store(this.getFormData())
        .then(res => resolve(res))
        .catch(res => {
          this.validator.setErrors(res)
          reject(res)
        })
    })
  }
}
