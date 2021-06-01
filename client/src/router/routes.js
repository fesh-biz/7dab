import AuthRoutes from './routes/auth'
import PageRoutes from './routes/pages'

const routes = [
  {
    path: '/auth',
    component: () => import('layouts/MainLayout.vue'),
    children: AuthRoutes
  },
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: PageRoutes
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
