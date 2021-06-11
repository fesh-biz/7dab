export default [
  {
    path: '/add-post',
    name: 'add_post',
    meta: {
      title: 'add_post'
    },
    component: () => import('pages/post/AddPost')
  }
]
