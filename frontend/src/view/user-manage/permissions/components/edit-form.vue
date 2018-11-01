<template>
    <div>
        <Modal
                ref="editPermissionModal"
                v-model="showModal"
                title="Title"
                :loading="loading"
                @on-ok="handleSubmit('editPermissionForm')"
                @on-cancel="handleReset('editPermissionForm')">
            <p slot="header">编辑权限</p>
            <Form ref="editPermissionForm" :model="permission" :rules="rules" :label-width="80">
                <FormItem label="权限" prop="name">
                    <Input v-model="permission.name" :placeholder='$t("Enter Permission name")'></Input>
                </FormItem>
                <FormItem label="备注">
                    <Input v-model="permission.comment"></Input>
                </FormItem>
            </Form>
        </Modal>
    </div>
</template>

<script>
    import { getPermission,updatePermission } from '@/api/permissions'
    export default {
        name: 'edit-permission-madel',
        props: {
            addModal: Boolean,
        },
        data() {
            return {
                showModal: this.addModal,
                loading: false,
                permission: {
                    name: '',
                    comment: ''
                },
                permissionId: 0,
                rules: {
                    name: [
                        { required: true, message: 'The name cannot be empty', trigger: 'blur' }
                    ],
                },
            }
        },

        methods: {
            handleSubmit (name) {
                let _this = this;

                _this.$refs[name].validate((valid) => {
                    if (valid) {
                        console.log(valid);
                        updatePermission(_this.permissionId,_this.permission.name,_this.permission.comment).then(res => {
                            this.$Message.success('更新权限成功');
                            _this.$emit('refreshTable',false)
                        })
                    } else {
                        _this.$refs.editPermissionModal.visible = true;
                        _this.showModal = true;
                    }
                })
            },
            handleReset (name) {
                this.$refs[name].resetFields();
//            _this.showAddModel = false;
//            _this.$emit('showModel',false)
            },
            open (id) {
                let _this = this;
                _this.permissionId = id;
                getPermission(id).then(res => {
                    _this.permission.name = res.data.data.name;
                    _this.permission.comment = res.data.data.comment;
                    _this.showModal = true;
                })
            }
        }

    }
</script>