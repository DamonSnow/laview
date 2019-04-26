import axios from '@/libs/api.request'

export const uploadImg = (formdata) => {

    return axios.request({
        url: 'uploadImg',
        method: 'post',
        data: formdata,
        headers: { 'Content-Type': 'multipart/form-data' },
    })
}