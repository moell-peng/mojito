import http from '../libs/http'

export const getAdminUserList = (params) => {
  return http.get('/api/admin-user', {
    params
  })
}

export const getUserRoles = (id, provider) => {
  return http.get(`/api/admin-user/${id}/roles/${provider}`)
}

export const assginRoles = (id, provider, roles) => {
  return http.put(`/api/admin-user/${id}/roles/${provider}`, {
    roles
  })
}

export const addAdminUser = (data) => {
  return http.post('/api/admin-user', data)
}

export const editAdminUser = (id, data) => {
  return http.patch(`/api/admin-user/${id}`, data)
}

export const deleteAdminUser = id => {
  return http.delete(`/api/admin-user/${id}`)
}