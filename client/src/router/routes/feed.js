export default [
  {
    path: '/',
    name: 'home',
    meta: {
      title: 'home_page'
    },
    component: () => import('pages/feed/Main')
  }
]
