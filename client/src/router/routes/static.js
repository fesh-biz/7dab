export default [
  {
    path: '/rules',
    name: 'static.rules',
    meta: {
      title: 'Наші правила'
    },
    component: () => import('pages/static/Rules')
  },
  {
    path: '/about',
    name: 'static.about',
    meta: {
      title: 'Про цей проект'
    },
    component: () => import('pages/static/About')
  }
]
