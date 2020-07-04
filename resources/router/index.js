import Vue from 'vue'
import Router from 'vue-router'
import store from  '../store'
import routes from './routers'
import config from '../config'
import { getToken, getPermissions } from '../libs/auth'
import { Message } from 'element-ui'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  saveScrollPosition: true,
  routes
})


router.beforeEach((to, from, next) => {
  const provider = to.meta.provider
  if (provider) {
    const providerConfig = config[Vue.prototype.$provider]
    if (providerConfig.loginRouteName === to.name) {
      next()
    }

    if (Vue.prototype.$provider !== provider) {
      Message({
        message: 'Access not allowed, not current project route',
        type: 'error'
      })
      next({name: providerConfig.dashboardName})
    } else {
      let login = new Promise((resolve, reject) => {
        getToken(provider).then( token => {
          if (!token || !token.hasOwnProperty('token')) {
            reject({ name : providerConfig.loginRouteName})
          } else {
            if (!store.getters.token) {
              store.commit('SET_TOKEN', {token, provider})
            }
            resolve()
          }
        }).catch(error => {
          reject(error)
        })
      })

      let permission = new Promise((resolve, reject) => {
        if (!to.meta.permission) {
          resolve()
        } else {
          getPermissions(provider).then( permissions => {
            if (permissions.indexOf(to.meta.permission) < 0) {
              reject(`You do not have permission to access ${to.meta.permission}`)
            }
            resolve()
          }).catch(error => {
            reject(error)
          })
        }
      })

      Promise.all([login, permission]).then( result => {
        next()
      }).catch( error => {
        let varType = typeof error;
        if (varType === 'object') {
          next({name: error.name})
        } else {
          Message({
            message: error,
            type: 'error'
          })
          next({name: from.name})
        }
      })
    }
  } else {
    next()
  }
})

export default router