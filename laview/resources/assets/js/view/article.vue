<template>
    <div class="container">
        <Card>
            <p>
                {{ article.title }}
            </p>
            <p><img style="width: 100%;height: 30px"  :src="article.cover_image" alt=""></p>
            <p>{{article.description}}</p>
            <p v-highlightA v-html="article.content_html">{{article.content_html}}</p>
            <p>By {{ article.user_id }} At {{article.created_at}}</p>
            <p>By {{ article.user_id }} At {{article.created_at}}</p>
        </Card>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                article: {

                }
            }
        },
        mounted() {
            let id = this.$route.params.id
            console.log('article detail mounted.')
            window.axios.get(`/getArticleById/${id}`).then(res => {
                this.article = res.data.data
                console.log(res)
            })
        }
    }
</script>
