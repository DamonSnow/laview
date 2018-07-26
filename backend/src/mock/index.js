import Mock from 'mockjs'
import { login, logout, getUserInfo } from './login'
import { getTableData,getUsersTableData } from './data'

// 登录相关和获取用户信息
// Mock.mock(/\/login/, login)
// Mock.mock(/\/get_info/, getUserInfo)
Mock.mock(/\/logout/, logout)
Mock.mock(/\/get_table_data/, getTableData)
Mock.mock(/\/get_users_table_data/, getUsersTableData)

export default Mock
