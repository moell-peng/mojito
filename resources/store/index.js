import Vue from'vue'
import Vuex from 'vuex'
import app from './modules/app'
import tag from './modules/tag'
import menu from './modules/menu'
import permission from './modules/permission'
import login from './modules/login'
import plugin from './plugin'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    app,
    menu,
    permission,
    login,
    tag
  },
  plugins: [plugin]
})

export default store