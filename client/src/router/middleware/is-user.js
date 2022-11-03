import Me from 'src/models/user/me'

export default function isUser ({ next }) {
  return Me.query().first() ? next() : next({ name: 'login' })
}
