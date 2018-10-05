import { loadPermissions } from '../../api/permission'

const state = {
  permissions: []
}

const getters = {
  permissions: state => state.permissions,
}

const mutations = {
  SET_PERMISSIONS (state, permissions) {
    state.permissions = permissions
  }
}

const actions = {
  hasAnyPermission ({ commit }, permissions) {
    let object = {}
    permissions.forEach(permission => {
      console.log(state.permissions)
      object[permission] = state.permissions.indexOf(permission) >= 0
    })
    return object
  },
  loadPermissions ( { commit }) {
    return loadPermissions()
      .then(response => {
        commit('SET_PERMISSIONS', response.data.data)
        Promise.resolve()
      })
      .catch( error => {
        Promise.reject(error)
      })
  }
}

export default {
  state,
  getters,
  mutations,
  actions
}