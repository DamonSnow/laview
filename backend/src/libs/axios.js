import axios from 'axios'
import Cookies from 'js-cookie'
import { TOKEN_KEY } from '@/libs/util'
import store from '@/store'
// import { Spin } from 'iview'
class HttpRequest {
  constructor (baseUrl = baseURL) {
    this.baseUrl = baseUrl
    this.queue = {}
  }
  getInsideConfig () {
    const config = {
      baseURL: this.baseUrl,
      headers: {
        //
      }
    }
    return config
  }
  distroy (url) {
    delete this.queue[url]
    if (!Object.keys(this.queue).length) {
      // Spin.hide()
    }
  }
  interceptors (instance, url) {
    // 请求拦截
    instance.interceptors.request.use(config => {
      if (!config.url.includes('/users')) {
        config.headers['x-access-token'] = Cookies.get(TOKEN_KEY)
        config.headers['Authorization'] = 'Bearer ' + Cookies.get(TOKEN_KEY)
      }
      // 添加全局的loading...
      if (!Object.keys(this.queue).length) {
        // Spin.show() // 不建议开启，因为界面不友好
      }
      this.queue[url] = true
      return config
    }, error => {
      return Promise.reject(error)
    })
    // 响应拦截
    instance.interceptors.response.use(res => {
      console.log('response interceptor:')
      console.log(res)
      if (res.code === 401) {
        store.dispatch('refreshToken')
      }
      this.distroy(url)
      const { data, status } = res
      return { data, status }
    }, error => {
      this.distroy(url)
      return Promise.reject(error)
    })
  }
  request (options) {
    const instance = axios.create()
    options = Object.assign(this.getInsideConfig(), options)
    this.interceptors(instance, options.url)
    return instance(options)
  }
}
export default HttpRequest
