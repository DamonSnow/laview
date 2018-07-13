import env from './env'

const DEV_URL = 'https://www.easy-mock.com/mock/5b4749d075248127d0d874e9'
const PRO_URL = 'https://produce.com'

export default env === 'development' ? DEV_URL : PRO_URL
