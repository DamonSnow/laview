<template>
    <Modal v-model="showModal">
        <p slot="header" style="text-align:center">
            <Icon type="person-add"></Icon>
            <span>新增用户</span>
        </p>
        <div style="text-align:center">
            //表单主体

        </div>
        <div slot="footer">
            <Button @click="close">取消</Button>
            <Button type="info" :loading="modal_loading" @click="addUser">新增</Button>
        </div>
    </Modal>
</template>
<script>
    export default {
        name: 'add-user-modal',
        props : {
            addUserModal : Boolean,
        },
        watch: {
            addUserModal (newValue) {
                this.showModal = newValue
            }
        },
        data () {
            return {

                showModal: this.addUserModal,
                modal_loading: false,
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
                _this.modal_loading = true;
                console.log(_this.formItem);
                setTimeout(() => {
                    _this.modal_loading = false;
                    _this.showModal = false;
                    _this.$emit('on-complete',false);
                    _this.$Message.success('Successfully delete');
                }, 2000);
            },
            close () {
                this.showModal = false;
                this.$emit('on-complete',!this.addUserModal);
            },
            open () {
                this.showModal = true
            }
        },
        mounted: function() {
            console.log(this.showModal)
        }
    }
</script>