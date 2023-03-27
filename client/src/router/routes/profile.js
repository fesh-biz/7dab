import isUser from 'src/router/middleware/is-user'

export default [
  {
    path: '/',
    name: 'profile',
    meta: {
      title: 'profile',
      middleware: [isUser]
    },
    component: () => import('pages/profile/Index')
  }
]
