export default class MimeTypeValidator {
  static types = [
    { type: 'jpeg', signatures: ['ffd8ffe0', 'ffd8ffe1', 'ffd8ffe2', 'ffd8ffe3', 'ffd8ffe8'] },
    { type: 'gif', signatures: ['47 494638'] },
    { type: '', signatures: [] },
    { type: '', signatures: [] },
    { type: '', signatures: [] },
    { type: '', signatures: [] },
    { type: '', signatures: [] },
    { type: '', signatures: [] }
  ]

  static validateFile (file) {
    const fileReader = new FileReader()
    fileReader.onloadend = function (e) {
      const arr = (new Uint8Array(e.target.result)).subarray(0, 4)
      let header = ''
      for (let i = 0; i < arr.length; i++) {
        header += arr[i].toString(16)
      }
      console.log(header)
    }

    fileReader.readAsArrayBuffer(file.slice(0, 4))
  }

  static validateFiles (files) {
    for (const file of files) {
      this.validateFile(file)
    }
  }
}
