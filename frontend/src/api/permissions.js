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