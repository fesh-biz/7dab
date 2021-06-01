import { Cookies } from 'quasar'

export default function isUser ({ next }) {
  const me = Cookies.get('me')

  return me ? next() : next({ name: 'login' })
}
