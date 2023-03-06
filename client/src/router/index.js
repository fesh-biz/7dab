import Vue from 'vue'
import VueRouter from 'vue-router'

import routes from './routes'

Vue.use(VueRouter)

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Router instance.
 */

let Router = null
export default function ({ store, ssrContext }) {
  const yPositions = {}
  Router = new VueRouter({
    scrollBehavior: () => ({ x: 0, y: 0 }),
    routes,

    // Leave these as they are and change in quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    mode: process.env.VUE_ROUTER_MODE,
    base: process.env.VUE_ROUTER_BASE
  })

  Router.beforeEach((to, from, next, store) => {
    if (from.name) {
      yPositions[from.path] = window.pageYOffset
    }

    if (yPositions[to.path]) {
      setTimeout(() => {
        window.scrollTo(0, yPositions[to.path])
      }, 10)
    }

    if (!to.meta.middleware) {
      return next()
    }

    const middleware = to.meta.middleware

    const context = {
      to,
      from,
      next,
      store
    }

    return middleware[0]({
      ...context
    })
  })

  return Router
}

export {
  Router
}
