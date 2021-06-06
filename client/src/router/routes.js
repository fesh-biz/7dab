import AuthRoutes from './routes/auth'
import FeedRoutes from './routes/feed'

const routes = [
  {
    path: '/auth',
    component: () => import('layouts/MainLayout.vue'),
    children: AuthRoutes
  },
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: FeedRoutes
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
