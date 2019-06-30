import http from '../libs/http'

const basicRoute = '/api/permission-group'

export const getPermissionGroupList = (params) => {
  return http.get(basicRoute, {
    params
  })
}

export const getPermissionGroupAll = () => {
  return http.get(`${basicRoute}-all`)
}

export const guardNameForPermissions = (guardName) => {
  return http.get(`/api/guard-name-for-permissions/${guardName}`)
}

export const addPermissionGroup = (data) => {
  return http.post(basicRoute, data)
}

export const editPermissionGroup = (id, data) => {
  return http.patch(`${basicRoute}/${id}`, data)
}

export const deletePermissionGroup = id => {
  return http.delete(`${basicRoute}/${id}`)
}