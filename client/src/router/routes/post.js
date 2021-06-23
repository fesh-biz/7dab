import isUser from 'src/router/middleware/is-user'

export default [
  {
    path: '/add-post',
    name: 'add_post',
    meta: {
      title: 'add_post',
      middleware: isUser
    },
    component: () => import('pages/post/AddPost')
  },
  {
    path: '/posts/:id',
    name: 'postPage',
    component: () => import('pages/post/PostPage')
  }
]
