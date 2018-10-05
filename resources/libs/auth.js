import localforage from 'localforage'

const TOKEN = 'token:'

const PERMISSION = 'permissions:'

export const setToken = (token, provider) => {
  return localforage.setItem(getTokenKey(provider), token)
}

export const getToken = (provider) => {
  return localforage.getItem(getTokenKey(provider))
}

export const removeToken = (provider) => {
  return localforage.removeItem(getTokenKey(provider))
}

export const getTokenKey = (provider) => {
  return TOKEN + provider
}

export const setPermissions = (permissions, provider) => {
  return localforage.setItem(getPermissionKey(provider), permissions)
}

export const getPermissions = (provider) => {
  return localforage.getItem(getPermissionKey(provider))
}

export const getPermissionKey =  provider => {
  return PERMISSION + provider
}