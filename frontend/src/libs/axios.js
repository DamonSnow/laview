import axios from 'axios'
import Cookies from 'js-cookie'
import { TOKEN_KEY, isTokenExpired } from '@/libs/util'
import store from '@/store'
import router from '@/router/routers'
// import { Spin } from 'iview'
window.isRefreshing = false
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
          console.log(window.isRefreshing);
          if(!window.isRefreshing) {
            window.isRefreshing = true;
            store.dispatch('refreshToken').then(function () {
                config.headers['x-access-token'] = Cookies.get(TOKEN_KEY)
                config.headers['Authorization'] = 'Bearer ' + Cookies.get(TOKEN_KEY)
                window.isRefreshing = false;

            })
          }
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
        console.log(res)
      const { data, status } = res
      return { data, status }
    }, error => {
      // if( parseInt(error.response.status) === 401 ) {
      //   store.dispatch('handleLogOut'); //可能是token过期，清除它
      //   router.replace({ //跳转到登录页面
      //     path: 'login',
      //     query: { redirect: router.currentRoute.fullPath } // 将跳转的路由path作为参数，登录成功后跳转到该路由
      //   });
      // }
      this.destroy(url)
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
