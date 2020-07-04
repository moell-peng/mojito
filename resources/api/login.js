import http from '../libs/http'

export const login = ({ username, password, provider }) => {
  return http.post('/api/auth/login', {
    username,
    password,
    provider,
  })
}

export const logout = () => {
  return http.post('/api/auth/logout')
}