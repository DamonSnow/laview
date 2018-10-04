import { login, logout, getUserInfo, refreshToken } from '@/api/user'
import { setToken, setRefreshToken, getToken, getRefreshToken } from '@/libs/util'

export default {
  state: {
    userName: '',
    userId: '',
    avatorImgPath: '',
    token: getToken(),
    refresh_token: getRefreshToken(),
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
      state.token = token
      setToken(token)
    },
    setRefreshToken (state, refreshToken) {
      state.refresh_token = refreshToken
      setRefreshToken(refreshToken)
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
          commit('setToken', data.data.access_token)
          commit('setRefreshToken', data.data.refresh_token)
          resolve()
        }).catch(err => {
          reject(err)
        })
      })
    },
    // 退出登录
    handleLogOut ({ dispatch, state, commit }) {
      return new Promise((resolve, reject) => {
        logout(state.token).then(() => {
          commit('setToken', '')
          commit('setAccess', [])
          resolve()
        }).catch(err => {
          console.log(err)
          dispatch('refreshToken', state)
        })
        // 如果你的退出登录无需请求接口，则可以直接使用下面三行代码而无需使用logout调用接口
        // commit('setToken', '')
        // commit('setAccess', [])
        // resolve()
      })
    },
    // 获取用户相关信息
    getUserInfo ({ dispatch, state, commit }) {
      return new Promise((resolve, reject) => {
        getUserInfo(state.token).then(res => {
          const data = res.data.data
          commit('setAvator', data.avatar)
          commit('setUserName', data.name)
          commit('setUserId', data.user_id)
          commit('setAccess', data.access)
          resolve(data)
        }).catch(err => {
          console.log(err)
          dispatch('refreshToken', state)
          // reject(err)
        })
      })
    },
    refreshToken ({ dispatch, state, commit }) {
      return new Promise((resolve, reject) => {
        refreshToken(state.refresh_token).then(res => {
          const data = res.data
          commit('setToken', data.access_token)
          commit('setRefreshToken', data.refresh_token)
          dispatch('getUserInfo', state)
          resolve(data)
        }).catch(err => {
          reject(err)
        })
      })
    }
  }
}
