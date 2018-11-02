import axios from '@/libs/api.request'

export const roles = (page, size) => {
  return axios.request({
    url: 'roles',
    params: {
      page,
      size,
    },
    method: 'get'
  })
}

export const addRole = (name, permissions, comment) => {
  let data = {
    name,
    permissions,
    comment
  };
  return axios.request({
    url: 'roles',
    data,
    method: 'post'
  })
}

export const getRole = (id) => {
  return axios.request({
    url: `roles/${id}`,
    method: 'get'
  })
}

export const updateRole = (id, name, permissions, comment) => {
  let data = {
    name,
    permissions,
    comment
  };
  return axios.request({
    url: `roles/${id}`,
    data,
    method: 'put'
  })
}

export const deleteRole = (id) => {
    return axios.request({
        url: `roles/${id}`,
        method: 'delete'
    })
}
