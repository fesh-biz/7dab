export default class Main {
  constructor () {
    if (Main.instance) {
      return Main.instance
    }

    this.isLastFetched = false
    this.currentPage = 0

    Main.instance = this
  }
}
