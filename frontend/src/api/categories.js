import axios from '@/libs/api.request'

export const categories = (page, size) => {
    return axios.request({
        url: 'categories',
        params: {
            page,
            size,
        },
        method: 'get'
    })
}

export const getChildrenCategories = (parent_id) => {
    return axios.request({
        url: `children_categories/${parent_id}`,
        method: 'get'
    })
}

export const addCategory = (categories) => {
    const data = Object.assign({},categories);
    return axios.request({
        url: 'categories',
        data,
        method: 'post'
    })
}

export const updateCategory = (id, categories) => {
    const data = Object.assign({},categories);
    return axios.request({
        url: `categories/${id}`,
        data,
        method: 'put'
    })
}

export const deleteCategory = (id) => {
    return axios.request({
        url: `categories/${id}`,
        method: 'delete'
    })
}

export const categoryTree = () => {
    return axios.request({
        url: `categoryTree`,
        method: 'get'
    })
}
