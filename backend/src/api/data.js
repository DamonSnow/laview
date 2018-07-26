import axios from '@/libs/api.request'

export const getTableData = () => {
  return axios.request({
    url: 'get_table_data',
    method: 'get'
  })
}

export const getUsersTableData = () => {
    return axios.request({
        url: 'get_users_table_data',
        method: 'get'
    })
}