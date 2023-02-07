import AuthRoutes from './routes/auth'
import FeedRoutes from './routes/feed'
import PostRoutes from './routes/post'
import AdminRoutes from './routes/admin'

import isAdmin from 'src/router/middleware/is-admin'

const routes = [
  {
    path: '/auth',
    component: () => import('layouts/MainLayout.vue'),
    children: AuthRoutes
  },
  // Feed routes
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: FeedRoutes
  },
  // Post routes
  {
    path: '/',
    component: () => import('layouts/MainLayout'),
    children: PostRoutes
  },
  // Admin routes
  {
    path: '/',
    meta: {
      middleware: [isAdmin]
    },
    component: () => import('layouts/Admin'),
    children: AdminRoutes
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
