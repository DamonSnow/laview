<template>
    <div>
        <Modal
                v-model="showModal"
                title="编辑用户角色"
                :loading="loading">
            <p slot="header">编辑用户角色</p>
            <p>编辑用户角色使用form表单实现</p>
        </Modal>
    </div>
</template>
<script>
    export default {
        name: 'edit-user-role-modal',
        props : {
            editModal : Boolean,
        },
        data () {
            return {
                showModal: this.editModal,
                loading: false,
                formItem: {
                    jobNum: '',
                    name: '',
                    dept: '',
                    email: '',
                    phone: ''
                }
            }
        },

        methods: {
            addUser () {
                let _this=this;
                _this.loading = true;
                console.log(_this.formItem);
                setTimeout(() => {
                    _this.loading = false;
                    _this.showModal = false;
//                    _this.$emit('on-complete',false);
                    _this.$Message.success('Successfully create');
                }, 2000);
            },
            close () {
                this.showModal = false;
//                this.$emit('on-complete',!this.addUserModal);
            },
            open (row) {
                this.showModal = true;
            }
        },
        mounted: function() {
            console.log(this.currentValue)
        }
    }
</script>