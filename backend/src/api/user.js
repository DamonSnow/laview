import axios from '@/libs/api.request'

export const login = ({ email, password }) => {
  const data = {
    email,
    password
  }
  return axios.request({
    url: 'login',
    data,
    method: 'post'
  })
}

export const getUserInfo = (token) => {
  return axios.request({
    url: 'get_info',
    params: {
      token
    },
    method: 'get'
  })
}

export const logout = (token) => {
  return axios.request({
    url: 'logout',
    method: 'post'
  })
}

export const refreshToken = (refreshToken) => {
  const data = {
    refresh_token: refreshToken
  }
  return axios.request({
    url: 'refresh',
    data,
    method: 'post'
  })
}
