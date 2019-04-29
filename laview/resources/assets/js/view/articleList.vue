<template>
    <div class="container">
        <Card style="margin-top: 16px;" v-for="(article,index) in articles" :key="index">
            <p>
                <router-link :to="{path:'/article/' + article.id}">
                    {{ article.title }}
                </router-link>
            </p>
            <p><img style="width: 100%;height: 30px"  :src="article.cover_image" alt=""></p>
            <p>{{article.description}}</p>
            <p>By {{ article.user_id }} At {{article.created_at}}</p>
        </Card>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                articles: []
            }
        },
        mounted() {
            console.log('Component mounted.')
            window.axios.get('/getArticles/1').then(res => {
                this.articles = res.data.data
                console.log(res)
            })
        }
    }
</script>
