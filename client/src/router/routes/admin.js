import isAdmin from 'src/router/middleware/is-admin'

export default [
  {
    path: 'admin',
    name: 'admin.home',
    meta: {
      middleware: [isAdmin],
      title: 'home'
    },
    component: () => import('pages/admin/Index')
  },
  // Tags
  {
    path: 'admin/tags',
    name: 'admin.tags',
    meta: {
      middleware: [isAdmin],
      title: 'tags'
    },
    component: () => import('pages/admin/Tags')
  },
  // Posts
  {
    path: 'admin/posts',
    name: 'admin.posts',
    meta: {
      middleware: [isAdmin],
      title: 'posts'
    },
    component: () => import('pages/admin/post/Index')
  }
]
