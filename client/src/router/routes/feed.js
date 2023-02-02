export default [
  {
    path: '/',
    name: 'home',
    meta: {
      title: 'terevenky'
    },
    component: () => import('pages/feed/Main')
  }
]
