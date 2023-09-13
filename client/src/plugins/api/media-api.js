import Api from 'src/plugins/api/api'

export default class MediaApi {
  constructor () {
    if (MediaApi.instance) {
      return MediaApi.instance
    }

    this.api = new Api()

    MediaApi.instance = this
  }

  checkFileType (file) {
    return new Promise((resolve, reject) => {
      const formData = new FormData()
      formData.append('file_chunk', file.slice(0, 100))
      formData.append('name', file.name)
      formData.append('size', file.size)

      this.api.simplePost('/media/check-file', formData)
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
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
          uploadProgress((loaded / total) * 100)
        }
      })
        .then(res => resolve(res))
        .catch(err => reject(err))
    })
  }
}
