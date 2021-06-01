import { api } from 'boot/axios'
import Me from 'src/models/user/Me'
import { routerInstance as router } from 'boot/router'
import { Cookies } from 'quasar'

export default class auth {
  static async loginAndRedirect (accessToken, user, routeName) {
    const bearer = 'Bearer ' + accessToken
    api.defaults.headers.common.Authorization = bearer
    Cookies.set('bearer', bearer, { path: '/' })
    await Me.create({
      data: user
    })

    Cookies.set('me', user, { path: '/' })

    routeName = routeName || 'home'
    router.push({ name: routeName })
  }
}
