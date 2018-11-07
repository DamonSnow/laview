import axios from '@/libs/api.request'

export const dicTypes = (page, size) => {
  return axios.request({
    url: 'dic_types',
    params: {
      page,
      size,
    },
    method: 'get'
  })
}

export const allDicTypes = () => {
  return axios.request({
    url: 'all_dic_types',
    method: 'get'
  })
}

export const addDicType = (dicTypes) => {
  const data = Object.assign({},dicTypes);
  return axios.request({
    url: 'dic_types',
    data,
    method: 'post'
  })
}

export const updateDicType = (id, dicTypes) => {
  const data = Object.assign({},dicTypes);
  return axios.request({
    url: `dic_types/${id}`,
    data,
    method: 'put'
  })
}
