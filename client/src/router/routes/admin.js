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
  // Users
  {
    path: 'admin/users',
    name: 'admin.users',
    meta: {
      middleware: [isAdmin],
      title: 'users'
    },
    component: () => import('pages/admin/user/Index')
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
  },
  {
    path: 'admin/posts/:id/preview',
    name: 'admin.posts.preview',
    meta: {
      middleware: [isAdmin],
      title: 'posts'
    },
    component: () => import('pages/admin/post/Preview')
  }
]
