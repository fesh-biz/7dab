import Me from 'src/models/user/Me'

export default function isUser ({ next }) {
  return Me.query().first() ? next() : next({ name: 'login' })
}
