import { login, logout, getUserInfo } from '@/api/user'
import { setToken, getToken } from '@/libs/util'

export default {
  state: {
    userName: '',
    userId: '',
    avatorImgPath: '',
    access_token: getToken(),
    access: ''
  },
  mutations: {
    setAvator (state, avatorPath) {
      state.avatorImgPath = avatorPath
    },
    setUserId (state, id) {
      state.userId = id
    },
    setUserName (state, name) {
      state.userName = name
    },
    setAccess (state, access) {
      state.access = access
    },
    setToken (state, token) {
      state.access_token = token
      setToken(token)
    }
  },
  actions: {
    // 登录
    handleLogin ({ commit }, {email, password}) {
      email = email.trim()
      return new Promise((resolve, reject) => {
        login({
          email,
          password
        }).then(res => {
          const data = res.data
          commit('setToken', data.access_token)
          resolve()
        }).catch(err => {
          reject(err)
        })
      })
    },
    // 退出登录
    handleLogOut ({ state, commit }) {
      return new Promise((resolve, reject) => {
        logout(state.access_token).then(() => {
          commit('setToken', '')
          commit('setAccess', [])
          resolve()
        }).catch(err => {
          reject(err)
        })
        // 如果你的退出登录无需请求接口，则可以直接使用下面三行代码而无需使用logout调用接口
        // commit('setToken', '')
        // commit('setAccess', [])
        // resolve()
      })
    },
    // 获取用户相关信息
    getUserInfo ({ state, commit }) {
      return new Promise((resolve, reject) => {
        getUserInfo(state.access_token).then(res => {
          const data = res.data
            console.log(data);
          commit('setAvator', data.avatar)
          commit('setUserName', data.name)
          commit('setUserId', data.id)
          commit('setAccess', data.active)
          resolve(data)
        }).catch(err => {
          reject(err)
        })
      })
    }
  }
}
