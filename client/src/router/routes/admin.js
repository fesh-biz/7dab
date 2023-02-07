
export default [
  {
    path: 'admin',
    name: 'admin.home',
    meta: {
      title: 'home'
    },
    component: () => import('pages/admin/Index')
  },
  {
    path: 'admin/tags',
    name: 'admin.tags',
    meta: {
      title: 'tags'
    },
    component: () => import('pages/admin/Tags')
  }
]
