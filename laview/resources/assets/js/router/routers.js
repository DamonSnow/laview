export default [
    // ExampleComponent laravel默认的示例组件
    { path: '/', component: require('../view/articleList.vue') },
    { path: '/article/:id', component: require('../view/article.vue') },
]