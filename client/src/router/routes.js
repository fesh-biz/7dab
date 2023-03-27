import AuthRoutes from './routes/auth'
import FeedRoutes from './routes/feed'
import PostRoutes from './routes/post'
import AdminRoutes from './routes/admin'
import ProfileRoutes from './routes/profile'
import ExternalLinks from './routes/external-links'

const routes = [
  // Auth routes
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
  // External links routes
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: ExternalLinks
  },
  // Admin routes
  {
    path: '/',
    component: () => import('layouts/Admin'),
    children: AdminRoutes
  },
  // Admin routes
  {
    path: '/profile',
    component: () => import('layouts/Profile'),
    children: ProfileRoutes
  },

  // Test Only
  {
    path: '/test',
    name: 'test',
    component: () => {
      if (process.env.ENV_DEV === 'Development') {
        return import('pages/test-only/Index')
      }
    }
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
