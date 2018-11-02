import axios from '@/libs/api.request'

export const permissions = (page, size) => {
  return axios.request({
    url: 'permissions',
    params: {
      page,
      size,
    },
    method: 'get'
  })
}

export const addPermission = (name, comment) => {
  let data = {
    name,
    comment
  };
  return axios.request({
    url: 'permissions',
    data,
    method: 'post'
  })
}

export const allPermissions = () => {
  return axios.request({
    url: 'all_permissions',
    method: 'get'
  })
}

export const getPermission = (id) => {
  return axios.request({
    url: `permissions/${id}`,
    method: 'get'
  })
}

export const updatePermission = (id, name, comment) => {
  let data = {
    name,
    comment
  };
  return axios.request({
    url: `permissions/${id}`,
    data,
    method: 'put'
  })
}

export const deletePermission = (id) => {
  return axios.request({
    url: `permissions/${id}`,
    method: 'delete'
  })
}
