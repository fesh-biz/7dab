import Validator from 'src/plugins/tools/validator'
import PostApi from 'src/plugins/api/post'
import _ from 'lodash'
import PostModel from 'src/models/content/post'
import { Notify } from 'quasar'

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
    this.totalImages = 0
    this.totalImagesMax = 10

    this.validator = new Validator(formModel)

    Post.instance = this
  }

  addSection (sectionType, content, id) {
    const nextOrder = !this.formModel.sections.length ? 1 : this.formModel.sections[this.formModel.sections.length - 1].order + 1

    content = content || null
    if (sectionType === 'youtube' && !content) {
      content = {
        youtube_id: null,
        title: null
      }
    }

    this.formModel.sections.push({
      id: id,
      order: nextOrder,
      type: sectionType,
      content: content
    })

    if (sectionType === 'image') {
      this.totalImages++
    }
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
    if (section?.type === 'image') {
      this.totalImages--

      if (this.totalImages < 0) {
        this.totalImages = 0
      }
    }
    this.formModel.sections.splice(sectionIndex, 1)
    this.validator.resetFieldError('sections', section.order)
  }

  fillFormModel (postId) {
    this.totalImages = 0
    this.formModel = _.cloneDeep(formModel)

    const post = PostModel.query().withAll().find(postId)
    this.formModel.title = post.title

    const sectionFiller = (section) => {
      const fillers = {
        text: (section) => {
          this.addSection('text', section.body, section.id)
        },
        image: (section) => {
          this.addSection(
            'image',
            {
              url: section.original_file_path,
              title: section.title
            },
            section.id)
        },
        youtube: (section) => {
          this.addSection(
            'youtube',
            {
              title: section.title,
              youtube_id: section.youtube_id
            },
            section.id)
        }
      }

      const type = section.type
      if (!fillers[type]) {
        throw new Error('Section with given type ' + type + ' doesn\'t exists')
      }

      fillers[type](section)
    }

    post.content.forEach(section => {
      sectionFiller(section)
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

    if (section.type !== 'image') {
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
    this.totalImages = 0
  }

  getSectionsWithCorrectOrders (sections) {
    sections = _.sortBy(this.formModel.sections, 'order')

    let order = 1
    sections = sections.map(s => {
      s.order = order
      order++
      return s
    })

    return sections
  }

  getFormData () {
    this.formModel.sections = this.getSectionsWithCorrectOrders(this.formModel.sections)
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

      if (section.type === 'youtube') {
        formData.append(`sections[${i}][content][title]`, section.content.title)
        formData.append(`sections[${i}][content][youtube_id]`, section.content.youtube_id)
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

  clearAllEmptyFields () {
    this.formModel.sections = this.formModel.sections.filter(section => section.content)
  }

  saveOrUpdate (postId) {
    return new Promise((resolve, reject) => {
      this.clearAllEmptyFields()

      if (!this.formModel.sections.length) {
        Notify.create({
          position: 'center',
          color: 'negative',
          message: 'Теревенька має мати хоча б одне текстове поле або поле з зображенням'
        })

        return reject()
      }

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
