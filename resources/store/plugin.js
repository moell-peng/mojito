import { setPermissions } from '../libs/auth'
import { setHttpToken } from '../libs/http'

const subscribe = (store) => {
  store.subscribe((mutation, state) => {
    switch (mutation.type) {
      case 'SET_TOKEN':
        setHttpToken(state.login.token.token)
        break;
      case 'SET_PERMISSIONS':
        setPermissions(state.permission.permissions, state.login.provider)
        break;
    }
  })
}

export default (store) => {
  subscribe(store)
};