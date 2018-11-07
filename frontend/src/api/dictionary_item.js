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
