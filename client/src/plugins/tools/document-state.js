export default class DocumentState {
  static isAllImagesLoaded () {
    return new Promise((resolve) => {
      const images = document.images
      if (!images.length) {
        resolve()
        return
      }

      Promise.all(Array.from(images).filter(img => !img.complete).map(img => new Promise(resolve => { img.onload = img.onerror = resolve }))).then(() => {
        setTimeout(() => {
          resolve()
        }, 100)
      })
    })
  }
}
