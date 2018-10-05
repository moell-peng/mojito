import store from '../store'

export const hasPermission = (name) => {
  return store.getters.permissions.indexOf(name) >= 0
}