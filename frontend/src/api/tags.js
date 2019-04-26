import axios from '@/libs/api.request'

export const tags = (page, size) => {
    return axios.request({
        url: 'tags',
        params: {
            page,
            size,
        },
        method: 'get'
    })
}

export const addTag = (tag) => {
    const data = Object.assign({},tag);
    return axios.request({
        url: 'tags',
        data,
        method: 'post'
    })
}

export const updTag = (id, tag) => {
    const data = Object.assign({},tag);
    return axios.request({
        url: `tags/${id}`,
        data,
        method: 'put'
    })
}

export const searchTags = (query) => {
    return axios.request({
        url: `searchTags/${query}`,
        method: 'get'
    })
}
