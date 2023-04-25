import AuthRoutes from './routes/auth'
import FeedRoutes from './routes/feed'
import PostRoutes from './routes/post'
import AdminRoutes from './routes/admin'
import ProfileRoutes from './routes/profile'
import ExternalLinks from './routes/external-links'
import UserRoutes from './routes/user'

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
    component: () => import('layouts/MainLayout'),
    children: ExternalLinks
  },
  // Admin routes
  {
    path: '/',
    component: () => import('layouts/Admin'),
    children: AdminRoutes
  },
  // Profile routes
  {
    path: '/profile',
    component: () => import('layouts/MainLayout'),
    children: ProfileRoutes
  },
  // User routes
  {
    path: '/users',
    component: () => import('layouts/MainLayout'),
    children: UserRoutes
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
