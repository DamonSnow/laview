import axios from 'axios'
import config from './config'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = config.baseUrl;
/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

axios.interceptors.response.use(
    respond => {
        return respond;
    },
    error => {
        console.log('Oops..Error occur');
        if (error.response && error.response.status) {
            swal(
                'Oops...',
                'Something went wrong!',
                'error'
            );
        }
        return Promise.reject(error);
    }
);

export default axios