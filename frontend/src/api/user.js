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

export const users = (page, size, jobNum, search_data) => {
  return axios.request({
    url: 'users',
    params: {
        page,
        size,
        jobNum,
        search_data,
    },
    method: 'get'
  })
}

export const addUser = (user) => {
    const data = Object.assign({},user);
    return axios.request({
        url: 'users',
        data,
        method: 'post'
    })
}

export const updateUser = (id, user) => {
    const data = Object.assign({},user);
    return axios.request({
        url: `users/${id}`,
        data,
        method: 'put'
    })
}

export const updateUserRole = (id, tagetRoles) => {
    const data = {
        tagetRoles
    };
    return axios.request({
        url: `update_user_role/${id}`,
        data,
        method: 'post'
    })
}

export const getUnreadCount = () => {
    return axios.request({
        url: 'message/count',
        method: 'get'
    })
}

export const getMessage = () => {
    return axios.request({
        url: 'messages',
        method: 'get'
    })
}

export const getContentByMsgId = msg_id => {
    return axios.request({
        url: `messages/${msg_id}`,
        method: 'get',
    })
}

export const hasRead = msg_id => {
    return axios.request({
        url: `messages/${msg_id}`,
        method: 'put',
        data: {
            read: 1
        }
    })
}

export const removeReaded = msg_id => {
    return axios.request({
        url: `messages/${msg_id}`,
        method: 'put',
        data: {
            read: -1
        }
    })
}

export const restoreTrash = msg_id => {
    return axios.request({
        url: `messages/${msg_id}`,
        method: 'put',
        data: {
            read: 1
        }
    })
}
