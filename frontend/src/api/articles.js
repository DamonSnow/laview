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