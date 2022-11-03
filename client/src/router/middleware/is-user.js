import MeCookies from 'src/plugins/cookies/me'
import Token from 'src/plugins/cookies/token'

const me = new MeCookies()
const token = new Token()

export default function isUser ({ next }) {
  return me.get() && !token.getIsExpired() ? next() : next({ name: 'login' })
}
