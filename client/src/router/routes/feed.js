export default [
  {
    path: '/',
    name: 'home',
    component: () => import('pages/feed/Main')
  },
  {
    path: '/search',
    name: 'search',
    meta: {
      title: 'search'
    },
    component: () => import('pages/feed/Search')
  },
  {
    path: '/anthem',
    name: 'anthem',
    meta: {
      title: 'anthem'
    },
    component: () => import('pages/feed/Anthem')
  }
]
