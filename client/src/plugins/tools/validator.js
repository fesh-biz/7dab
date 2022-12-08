import { i18n } from 'boot/i18n'

export default class Validator {
  constructor (formModel) {
    this.formModel = formModel
    this.errors = {
      error_message: null
    }

    if (this.formModel) {
      this.resetErrors()
    }
  }

  setError (field, errors) {
    this.errors[field] = errors[field] ? errors[field][0] : null
  }

  setErrors (res) {
    const validation = res.response.data.errors
    if (!validation) {
      const errors = {
        invalid_grant: i18n.messages[i18n.locale].wrong_credentials,
        invalid_request: i18n.messages[i18n.locale].check_your_data
      }
      const errorType = res.response.data.error
      this.errors.error_message = errors[errorType] || null

      return
    }

    for (const field in validation) {
      if (validation.hasOwnProperty(field)) {
        if (field === 'error_message') {
          this.errors.error_message = validation[field]
          continue
        }
        this.errors[field] = validation[field][0] || validation[field]
      }
    }
  }

  resetFieldError (fieldName, order) {
    if (this.errors[fieldName]) {
      if (order) {
        if (typeof this.errors[fieldName] === 'string') {
          this.errors[fieldName] = null
        } else {
          this.errors[fieldName][order] = null
        }
      } else {
        this.errors[fieldName] = null
      }
    }
    this.errors.error_message = null
  }

  resetErrors () {
    for (const name in this.formModel) {
      if (this.formModel.hasOwnProperty(name)) {
        this.errors[name] = null
      }
    }
  }
}
