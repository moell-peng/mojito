import http from '../libs/http'

export const changePassword = (params) => {
  return http.patch('api/user-change-password', params)
}
