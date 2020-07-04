import { login, logout } from '../../api/login'
import { removeToken, setToken } from '../../libs/auth'

const state = {
  token: '',
  provider: ''
}

const getters = {
  token: state => state.token,
  accessToken : state => state.token.access_token,
}

const mutations = {
  SET_TOKEN (state, {token, provider}) {
    state.token = token
    state.provider = provider
  }
}

const actions = {
  loginHandle ({ commit }, { username, password, provider }) {
    return new Promise((resolve, reject) => {
      return login(arguments[1])
        .then(response => {
          const token = response.data.data

          commit('SET_TOKEN', {token, provider})

          resolve(setToken(token, provider))
        })
        .catch(error => {
          reject(error)
        })
    })
  },

  logoutHandle ({ commit }, provider ) {
    return new Promise((resolve, reject) => {
      return logout().then( () => {
        removeToken(provider)
      }).catch( error => {
        reject(error)
      })
    })
  }
}

export default {
  state,
  getters,
  mutations,
  actions
}