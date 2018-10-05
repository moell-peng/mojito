import http from '../libs/http'

export const login = ({ username, password, clientId, clientSecret, provider }) => {
  return http.post('/oauth/token', {
    username,
    password,
    provider,
    grant_type: 'password',
    client_id: clientId,
    client_secret: clientSecret
  })
}

export const logout = () => {

}