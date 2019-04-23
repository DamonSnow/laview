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

export const addRole = (role) => {
  const data = Object.assign({},role);
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

export const allRoles = () => {
  return axios.request({
    url: `all_roles`,
    method: 'get'
  })
}

export const updateRole = (id, role) => {
  const data = Object.assign({},role);
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

export const permissionByRoleId = (id) => {
    return axios.request({
        url: `permissionByRoleId/${id}`,
        method: 'get'
    })
}

export const updRolePermission = (id, permissions) => {
    let data = {
        permissions,
    };
    return axios.request({
        url: `updRolePermission/${id}`,
        data,
        method: 'post'
    })
}
