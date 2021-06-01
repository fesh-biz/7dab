export default [
  {
    path: 'login',
    name: 'login',
    meta: {
      title: 'login'
    },
    component: () => import('pages/auth/Login')
  },
  {
    path: 'register',
    name: 'register',
    meta: {
      title: 'register'
    },
    component: () => import('pages/auth/Register')
  },
  {
    path: 'forgot-password',
    name: 'forgot_password',
    component: () => import('pages/auth/ForgotPassword')
  },
  {
    path: '/auth/reset-password/:token',
    name: 'reset_password',
    meta: {
      title: 'reset_password'
    },
    component: () => import('pages/auth/ResetPassword')
  }
]
