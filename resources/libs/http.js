import axios  from 'axios'
import config from '../config'
import { Message } from 'element-ui'

const httpRequest = axios.create({
  timeout: 5000,
  baseURL: config.apiUrl
})

httpRequest.interceptors.request.use(
  config => {
    return config
  },
  error => {
    return Promise.reject(error)
  }
)

export function setHttpToken(token) {
  httpRequest.defaults.headers.common.Authorization = `Bearer ${token}`
}

httpRequest.interceptors.response.use(
  response => {
    return response
  },
  error => {
    let message = error.response.data.message ? error.response.data.message : error.response.statusText
    let dangerouslyUseHTMLString = false

    if (error.response.status === 422 && error.response.data.hasOwnProperty('errors')) {
      message += '<br>';
      for (let key in error.response.data.errors) {
        let items = error.response.data.errors[key]
        if (typeof items === 'string') {
          message += `${items} <br>`
        } else {
          error.response.data.errors[key].forEach( item => {
            message += `${item} <br>`
          })
        }
      }
      dangerouslyUseHTMLString = true
    }


    Message({
      dangerouslyUseHTMLString,
      message: message,
      type: 'error'
    })

    return Promise.reject(error)
  }
)

export default httpRequest
