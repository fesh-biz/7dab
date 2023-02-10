export default class DocumentState {
  static emitImagesUploaded () {
    const imagesUploadedEvent = new Event('imagesUploaded')
    const images = document.images
    if (!images.length) {
      dispatchEvent(imagesUploadedEvent)
      return
    }

    Promise.all(Array.from(images).filter(img => !img.complete).map(img => new Promise(resolve => {
      img.onload = img.onerror = resolve
    }))).then(() => {
      setTimeout(() => {
        dispatchEvent(imagesUploadedEvent)
      }, 100)
    })
  }
}
