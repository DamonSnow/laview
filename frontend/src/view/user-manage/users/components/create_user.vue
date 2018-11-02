<template>
  <div>
    <Modal
      ref="createUserModal"
      v-model="showModal"
      title="新增用户"
      :loading="loading"
      @on-ok="handleSubmit('userForm')"
      @on-cancel="handleReset('userForm')">
      <p slot="header">新增用户</p>
      <Form ref="userForm" :model="user" :rules="ruleValidate" :label-width="80">
        <FormItem label="姓名" prop="name">
          <Input v-model="user.name" :placeholder='$t("Enter User name")'></Input>
        </FormItem>
        <FormItem label="头像">
          <Input v-model="user.avatar"></Input>
        </FormItem>
        <FormItem label="E-mail" prop="email">
          <Input v-model="user.email"></Input>
        </FormItem>
        <FormItem label="工号" prop="job_number">
          <Input v-model="user.job_number"></Input>
        </FormItem>
        <FormItem label="手机号">
          <Input v-model="user.phone"></Input>
        </FormItem>
    </Form>
    </Modal>
   </div>
</template>
<script>
    import { addUser } from '@/api/user'
    export default {
        name: 'add-user-modal',
        props : {
            addUserModal : Boolean,
        },
        data () {
            return {
                showModal: this.addUserModal,
                loading: false,
                user: {
                    name: '',
                    avatar: '',
                    email: '',
                    job_number: '',
                    phone: '',
                },
                ruleValidate: {
                    name: [
                        { required: true, message: '姓名不能为空', trigger: 'blur' }
                    ],
                    email: [
                        { required: true, message: 'email不能为空', trigger: 'blur' },
                        { type: 'email', message: 'email格式不正确', trigger: 'blur' }
                    ],
                    job_number: [
                        { required: true, message: '工号不能为空', trigger: 'blur' }
                    ],
                }
            }
        },

        methods: {
            handleSubmit (name) {
                let _this=this;

                this.$refs[name].validate((valid) => {


                    this.$refs[name].validate((valid) => {
                        if (valid) {
                           addUser(_this.user).then(res => {
                               console.log(res.data)
                           }).catch(function (error) {
                               _this.showModal =true;
                               _this.$Message.error(error.response.data.message);
//                               _this.handleReset('userForm')
                           })
                        } else {
                            _this.$refs.createUserModal.visible = true;
                            _this.showModal = true;
                        }
                    })
                });

            },
            handleReset (name) {
                this.$refs[name].resetFields();
//            _this.showAddModel = false;
//            _this.$emit('showModel',false)
            },
            open () {
                this.showModal = true;
            }
        },
    }
</script>