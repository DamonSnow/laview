import axios from '@/libs/api.request'

export const getSchedules = (condition) => {
  const data = Object.assign({},condition);
  return axios.request({
    url: 'get_schedules',
    data,
    method: 'post'
  })
}

export const updScheTime = (id, condition) => {
    const data = Object.assign({},condition);
    return axios.request({
        url: `upd_schedule_time/${id}`,
        data,
        method: 'post'
    })
}

export const addSchedule = (condition) => {
    const data = Object.assign({},condition);
    return axios.request({
        url: 'schedules',
        data,
        method: 'post'
    })
}

export const delSchedule = (id) => {
    return axios.request({
        url: `schedules/${id}`,
        method: 'delete'
    })
}

export const updSchedule = (id, condition) => {
    const data = Object.assign({},condition);
    return axios.request({
        url: `schedules/${id}`,
        data,
        method: 'put'
    })
}

export const calendars = () => {
    return axios.request({
        url: 'calendars',
        method: 'get'
    })
}
