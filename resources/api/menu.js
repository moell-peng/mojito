import qs from 'qs'
import http from '../libs/http'

const basicRoute = '/api/menu'

export const myMenu = () => {
  return http.get('/api/my-menu')
}

export const getMenuList = (params) => {
  return http.get(basicRoute, {
    params
  })
}

export const addMenu = (data) => {
  return http.post(basicRoute, data)
}

export const editMenu = (id, data) => {
  return http.patch(`${basicRoute}/${id}`, qs.stringify(data))
}

export const deleteMenu = id => {
  return http.delete(`${basicRoute}/${id}`)
}