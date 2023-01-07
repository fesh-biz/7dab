import Validator from 'src/plugins/tools/validator'
import PostApi from 'src/plugins/api/post'
import _ from 'lodash'
import PostModel from 'src/models/content/post'

const formModel = {
  title: '',
  sections: [],
  tags: []
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

  addSection (sectionType, content, id) {
    const nextOrder = !this.formModel.sections.length ? 1 : this.formModel.sections[this.formModel.sections.length - 1].order + 1
    this.formModel.sections.push({
      id: id,
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
    const section = this.formModel.sections[sectionIndex]
    this.formModel.sections.splice(sectionIndex, 1)
    this.validator.resetFieldError('sections', section.order)
  }

  fillFormModel (postId) {
    this.formModel = _.cloneDeep(formModel)

    const post = PostModel.query().withAll().find(postId)
    console.log('post', post)
    this.formModel.title = post.title

    post.content.forEach(section => {
      if (section.type === 'text') {
        this.addSection('text', section.body, section.id)
      }

      if (section.type === 'image') {
        this.addSection(
          'image',
          {
            url: section.original_file_path,
            title: section.title
          },
          section.id)
      }
    })

    const tags = []
    post.tags.forEach(t => {
      tags.push({
        label: t.title,
        value: t.id
      })
    })

    this.formModel.tags = tags
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

      if (section.id) {
        formData.append(`sections[${i}][id]`, section.id)
      }
      formData.append(`sections[${i}][type]`, section.type)
      formData.append(`sections[${i}][order]`, section.order)

      if (section.type === 'image') {
        if (!section.content) continue
        if (section.content.file) {
          formData.append(`sections[${i}][content][file]`, section.content.file)
        }

        formData.append(`sections[${i}][content][url]`, section.content.url || '')
        formData.append(`sections[${i}][content][title]`, section.content.title || '')
      }

      if (section.type === 'text') {
        formData.append(`sections[${i}][content]`, section.content || '')
      }
    }

    for (let i = 0; i < model.tags.length; i++) {
      const tag = model.tags[i]

      if (tag.value) {
        formData.append(`tags[${i}][id]`, tag.value)
      } else {
        formData.append(`tags[${i}][new]`, tag)
      }
    }

    return formData
  }

  saveOrUpdate (postId) {
    return new Promise((resolve, reject) => {
      const formData = this.getFormData()

      const apiMethod = postId ? 'update' : 'store'

      this.api[apiMethod](formData, postId)
        .then(res => resolve(res))
        .catch(err => {
          this.validator.setErrors(err)
          reject(err)
        })
    })
  }
}
