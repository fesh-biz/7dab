import MeCookies from 'src/plugins/cookies/me'
import Token from 'src/plugins/cookies/token'

const me = new MeCookies()
const token = new Token()

export default function isAdmin ({ next }) {
  if (me.get() && !token.getIsExpired()) {
    if (me.get().role_id === 1) {
      return next()
    }
  }
  return next({ name: 'home' })
}
