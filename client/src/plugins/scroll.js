export function isScrollBottom (offset) {
  const pageHeight = document.documentElement.offsetHeight,
    windowHeight = window.innerHeight,
    scrollPosition = window.scrollY ||
      window.pageYOffset ||
      document.body.scrollTop + ((document.documentElement && document.documentElement.scrollTop) || 0)

  return pageHeight <= windowHeight + scrollPosition + (offset || 0)
}
