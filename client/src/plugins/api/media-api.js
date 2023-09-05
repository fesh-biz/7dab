import Api from 'src/plugins/api/api'

export default class MediaApi {
  constructor () {
    if (MediaApi.instance) {
      return MediaApi.instance
    }

    this.api = new Api()

    MediaApi.instance = this
  }

  upload (file, uploadProgress) {
    return new Promise((resolve, reject, progress) => {
      const formData = new FormData()
      formData.append('file', file)

      this.api.axios({
        url: '/media',
        method: 'post',
        data: formData,
        onUploadProgress: function ({ loaded, total, progress, bytes, estimated, rate, upload = true }) {
          console.log('loaded', loaded)
          console.log('total', total)
        }
      })
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }

  create (file) {
    return new Promise((resolve, reject, progress) => {
      const formData = new FormData()
      formData.append('file', file)

      this.api.post('/media', formData, null, null, true)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }
}
