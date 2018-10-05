import http from '../libs/http'

const basicRoute = '/api/role'

export const getRoleList = (params) => {
  return http.get(basicRoute, {
    params
  })
}

export const guardNameRoles = (guardName) => {
  return http.get(`/api/guard-name-roles/${guardName}`)
}

export const rolePermission = (id) => {
  return http.get(`/api/role/${id}/permissions`)
}

export const roleAssignPermission = (id, permissions) => {
  return http.put(`/api/role/${id}/permissions`, {
    permissions
  })
}

export const addRole = (data) => {
  return http.post(basicRoute, data)
}

export const editRole = (id, data) => {
  return http.patch(`${basicRoute}/${id}`, data)
}

export const deleteRole = id => {
  return http.delete(`${basicRoute}/${id}`)
}