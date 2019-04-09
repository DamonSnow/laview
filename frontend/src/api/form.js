import axios from '@/libs/api.request'

export const formLists = (page, size) => {
    return axios.request({
        url: 'forms',
        params: {
            page,
            size,
        },
        method: 'get'
    })
}

export const addForm = (form) => {
    const data = Object.assign({},form);
    return axios.request({
        url: 'forms',
        data,
        method: 'post'
    })
}

export const updateForm = (id, form) => {
    const data = Object.assign({},form);
    return axios.request({
        url: `forms/${id}`,
        data,
        method: 'put'
    })
}

export const toggleForm = (id, value) => {
    return axios.request({
        url: `toggle_form/${id}`,
        params: {
            enable: value,
        },
        method: 'get'
    })
}

export const getFormData = (id) => {
    return axios.request({
        url: `form_data/${id}`,
        method: 'get'
    })
}

export const saveFormData = (id, version, form_data) => {
    const data = Object.assign({},form_data);
    return axios.request({
        url: `form_data/${id}/${version}`,
        data,
        method: 'post'
    })
}
