export default class Url {
  constructor () {
    if (Url.instance) {
      return Url.instance
    }

    Url.instance = this
  }

  getUrlQueryVars (url) {
    const vars = {}
    url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
      vars[key] = value
    })

    return vars
  }
}
