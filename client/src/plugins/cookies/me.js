import { Cookies } from 'quasar'

export default class Me {
  constructor () {
    if (Me.instance) {
      return Me.instance
    }

    this.me = null

    Me.instance = this
  }

  get () {
    if (this.me) return this.me

    if (Cookies.get('me')) {
      this.me = Cookies.get('me')
    }

    return this.me
  }

  set (me) {
    Cookies.set('me', JSON.stringify(me), { path: '/' })
    this.me = me
  }

  delete () {
    Cookies.remove('me')
    this.me = null
  }
}
