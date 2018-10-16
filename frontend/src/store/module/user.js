import { login, logout, getUserInfo, refreshToken } from '@/api/user'
import { setToken, setRefreshToken, setExpiredIn, getToken, getRefreshToken, getExpiredIn } from '@/libs/util'

export default {
  state: {
    userName: '',
    userId: '',
    avatorImgPath: '',
    token: getToken(),
    refresh_token: getRefreshToken(),
    access: '',
    expired_in: 0,
    hasGetInfo: false
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
    setHasGetInfo (state, status) {
      state.hasGetInfo = status
    },
    setToken (state, token) {
      state.token = token
      setToken(token)
    },
    setRefreshToken (state, refreshToken) {
      state.refresh_token = refreshToken
      setRefreshToken(refreshToken)
    },
    setExpiredIn (state, expiredIn) {
      let expire = new Date().getTime() + expiredIn * 1000;
      state.expired_in = expire
      setExpiredIn(expire)
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
          commit('setExpiredIn', data.data.expires_in)
          resolve()
        }).catch(err => {
          reject(err)
        })
      })
    },
    // 退出登录
    handleLogOut ({ state, commit }) {
      return new Promise((resolve, reject) => {
        logout(state.token).then(() => {
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
        try {
          getUserInfo(state.token).then(res => {
            const data = res.data.data
            commit('setAvator', data.avatar)
            commit('setUserName', data.user_name)
            commit('setUserId', data.user_id)
            commit('setAccess', data.access)
            commit('setHasGetInfo', true)
            resolve(data)
          }).catch(err => {
            dispatch('refreshToken', state)
            reject(err)
          })
        } catch (error) {
          reject(error)
        }
      })
    },
    refreshToken ({ dispatch, state, commit }) {
      return new Promise((resolve, reject) => {
        refreshToken(state.refresh_token).then(res => {
          const data = res.data
          commit('setToken', data.access_token)
          commit('setRefreshToken', data.refresh_token)
          commit('setExpiredIn', data.expires_in)
          dispatch('getUserInfo', state)
          resolve(data)
        }).catch(err => {

          reject(err)
        })
      })
    }
  }
}
