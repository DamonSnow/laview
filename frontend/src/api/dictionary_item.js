import axios from '@/libs/api.request'

export const dicItems = (page, size) => {
  return axios.request({
    url: 'dic_items',
    params: {
      page,
      size,
    },
    method: 'get'
  })
}

export const addDicItem = (dicItem) => {
  const data = Object.assign({},dicItem);
  return axios.request({
    url: 'dic_items',
    data,
    method: 'post'
  })
}

export const updateDicItem = (id, dicItem) => {
  const data = Object.assign({},dicItem);
  return axios.request({
    url: `dic_items/${id}`,
    data,
    method: 'put'
  })
}

export const toggleDicItem = (id, value) => {
    return axios.request({
        url: `toggle_dic_items/${id}`,
        params: {
            enable: value,
        },
        method: 'get'
    })
}
