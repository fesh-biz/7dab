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

  uploadChunk (data, uploadProgress) {
    return new Promise((resolve, reject, progress) => {
      const formData = new FormData()
      formData.append('media_id', data.media_id)
      formData.append('file_chunk', data.file_chunk)
      formData.append('chunk_index', data.chunk_index)
      formData.append('total_chunks', data.total_chunks)

      this.api.axios({
        url: '/media/upload-chunk',
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
