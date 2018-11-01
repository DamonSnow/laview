<template>
    <div>
        <create-user ref="userModal" :add-user-modal="addUserModal" @on-complete="complete">

        </create-user>
        <Card>
            <Button @click="open" type="primary" >{{ $t('add-user') }}</Button>
        </Card>
    </div>
</template>

<script>
    import createUser from './components/create-user.vue'
    export default {
        components: {
            createUser
        },
        data () {
            return {
                addUserModal: false,
            }
        },
        methods: {
            complete (e) {
                this.addUserModal = e;
            },
            open() {
                this.$refs.userModal.open();
            }
        }
    }
</script>