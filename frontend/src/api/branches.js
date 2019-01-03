import axios from '@/libs/api.request'

export const addBranch = (branch) => {
    const data = Object.assign({},branch);
    return axios.request({
        url: 'branches',
        data,
        method: 'post'
    })
}

export const updateBranch = (id, branch) => {
    const data = Object.assign({},branch);
    return axios.request({
        url: `branches/${id}`,
        data,
        method: 'put'
    })
}

export const delBranch = (id, branch) => {
    const data = Object.assign({},branch);
    return axios.request({
        url: `branches/${id}`,
        data,
        method: 'put'
    })
}
