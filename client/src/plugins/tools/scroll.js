export default class Scroll {
  constructor () {
    if (Scroll.instance) {
      return Scroll.instance
    }

    Scroll.instance = this
  }

  isScrollBottom (offset) {
    const pageHeight = document.documentElement.offsetHeight,
      windowHeight = window.innerHeight,
      scrollPosition = window.scrollY ||
        window.pageYOffset ||
        document.body.scrollTop + ((document.documentElement && document.documentElement.scrollTop) || 0)

    return pageHeight <= windowHeight + scrollPosition + (offset || 0)
  }

  whenScrollFinished () {
    let lastChangedFrame = 0
    let lastX = window.scrollX
    let lastY = window.scrollY

    return new Promise(resolve => {
      function tick (frames) {
        if (frames >= 500 || frames - lastChangedFrame > 20) {
          resolve()
        } else {
          if (window.scrollX !== lastX || window.scrollY !== lastY) {
            lastChangedFrame = frames
            lastX = window.scrollX
            lastY = window.scrollY
          }
          requestAnimationFrame(tick.bind(null, frames + 1))
        }
      }
      tick(0)
    })
  }
}
