import axios from '@/libs/api.request'

export const getFormData = (id) => {
    return axios.request({
        url: `form_data/${id}`,
        method: 'get'
    })
}

export const addFormData = (id, form_data) => {
    const data = Object.assign({},form_data);
    return axios.request({
        url: `form_data/${id}`,
        data,
        method: 'post'
    })
}
