import axios from 'axios'
import Cookies from 'js-cookie'
import { TOKEN_KEY, isTokenExpired, REFRESH_TOKEN_KEY } from '@/libs/util'
import store from '@/store'
import router from 'vue-router'
import { refreshToken } from '@/api/user'
// import { Spin } from 'iview'
const addErrorLog = errorInfo => {
  const { statusText, status, request: { responseURL } } = errorInfo
  let info = {
    type: 'ajax',
    code: status,
    mes: statusText,
    url: responseURL
  }
  if (!responseURL.includes('save_error_logger')) store.dispatch('addErrorLog', info)
}
window.isRefreshing = false
/*被挂起的请求数组*/
let refreshSubscribers = []
/*push所有请求到数组中*/
function subscribeTokenRefresh (cb) {
    refreshSubscribers.push(cb)
}
/*刷新请求（refreshSubscribers数组中的请求得到新的token之后会自执行，用新的token去请求数据）*/
function onRrefreshed (token) {
    refreshSubscribers.map(cb => cb(token))
}
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
  destroy (url) {
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
        if(Cookies.get(TOKEN_KEY)) {
            if(isTokenExpired() && config.url.indexOf('refresh') === -1) {

                if(!window.isRefreshing) {
                    window.isRefreshing = true;
                    store.dispatch('refreshToken').then(function () {
                        config.headers['x-access-token'] = Cookies.get(TOKEN_KEY)
                        config.headers['Authorization'] = 'Bearer ' + Cookies.get(TOKEN_KEY)
                        window.isRefreshing = false;
                      /*执行数组里的函数,重新发起被挂起的请求*/
                        onRrefreshed(Cookies.get(TOKEN_KEY))
                      /*执行onRefreshed函数后清空数组中保存的请求*/
                        refreshSubscribers = []

                    })
                }
                let retryRequest = new Promise((resolve, reject) => {
                  /*(token) => {...}这个函数就是回调函数*/
                    subscribeTokenRefresh((token) => {
                        config.headers['x-access-token'] = Cookies.get(TOKEN_KEY)
                        config.headers['Authorization'] = 'Bearer ' + Cookies.get(TOKEN_KEY)
                      /*将请求挂起*/
                        resolve(config)
                    })
                })
                return retryRequest
            }
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

      this.destroy(url)
      const { data, status } = res
      return { data, status }
    }, error => {
      if( parseInt(error.response.status) === 401 ) {
        // refreshToken(Cookies.get(REFRESH_TOKEN_KEY)).then(response => {
        //   setToken(response.)
        // })

        router.replace({ //跳转到登录页面
          path: 'login',
        });
      }
      this.destroy(url)
      let errorInfo = error.response
      if (!errorInfo) {
        const { request: { statusText, status }, config } = JSON.parse(JSON.stringify(error))
        errorInfo = {
          statusText,
          status,
          request: { responseURL: config.url }
        }
      }
      addErrorLog(errorInfo)
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
