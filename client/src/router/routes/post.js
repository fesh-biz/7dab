import isUser from 'src/router/middleware/is-user'

export default [
  {
    path: '/posts/create',
    name: 'createPost',
    meta: {
      title: 'add_post',
      middleware: [isUser]
    },
    component: () => import('pages/post/Create')
  },
  {
    path: '/posts/:id/edit',
    name: 'editPost',
    meta: {
      title: 'edit_post',
      middleware: [isUser]
    },
    component: () => import('pages/post/Edit')
  },
  {
    path: '/posts/:id',
    name: 'postView',
    component: () => import('pages/post/PostView')
  }
]
