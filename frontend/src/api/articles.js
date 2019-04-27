import axios from '@/libs/api.request'

export const articles = (page, size) => {
    return axios.request({
        url: 'articles',
        params: {
            page,
            size,
        },
        method: 'get'
    })
}

export const addArticle = (article, categories, tags) => {
    const data = Object.assign({},article, categories, tags);
    return axios.request({
        url: 'articles',
        data,
        method: 'post'
    })
}

export const updArticle = (id, article, categories, tags) => {
    const data = Object.assign({},article, categories, tags);
    return axios.request({
        url: `articles/${id}`,
        data,
        method: 'put'
    })
}

export const getArticle = (id) => {
    return axios.request({
        url: `articles/${id}`,
        method: 'get'
    })
}
