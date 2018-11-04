<template>
  <div>
    <Modal
        ref="editUserModal"
        v-model="showModal"
        title="编辑用户信息"
        :loading="loading"
        @on-ok="handleSubmit('editUserForm')"
        @on-cancel="handleReset('editUserForm')">
      <p slot="header">编辑用户信息</p>
      <Alert type="error" v-if="errorMsg">{{ errorMsg }}</Alert>
      <Form ref="editUserForm" :model="user" :rules="ruleValidate" :label-width="80">
        <FormItem label="姓名" prop="name">
          <Input v-model="user.name" :placeholder='$t("Enter User name")'></Input>
        </FormItem>
        <FormItem label="头像">
          <Input v-model="user.avatar"></Input>
        </FormItem>
        <FormItem label="手机号">
          <Input v-model="user.phone"></Input>
        </FormItem>
        <FormItem label="激活">
          <i-switch v-model="user.active" size="large" :true-value="1" :false-value="0">
            <span slot="open">On</span>
            <span slot="close">Off</span>
          </i-switch>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>
<script>
  import { updateUser } from '@/api/user'
  export default {
    name: 'edit-user-modal',
    props : {
      addUserModal : Boolean,
    },
    data () {
      return {
        errorMsg: '',
        showModal: this.addUserModal,
        loading: false,
        userId: 0,
        user: {
          name: '',
          avatar: '',
          phone: '',
          active: 0
        },
        ruleValidate: {
          name: [
            { required: true, message: '姓名不能为空', trigger: 'blur' }
          ],
        }
      }
    },

    methods: {
      handleSubmit (name) {
        let _this=this;
        _this.errorMsg = '';
        this.$refs[name].validate((valid) => {
          if (valid) {
            updateUser(_this.userId, _this.user).then(res => {
              if(parseInt(res.data.code) === 200) {
                this.$Message.success('更新用户信息成功');
                _this.$emit('refreshTable',false)
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                _this.showModal = true;
              }
            }).catch(function (error) {
              _this.$refs.editUserModal.visible = true;
              _this.showModal =true;
              let message = '';
              for (let i in error.response.data.message) {
                message = message + error.response.data.message[i];
              }
              _this.errorMsg = message;
            })
          } else {
            _this.$refs.editUserModal.visible = true;
            _this.showModal = true;
          }
        })

      },
      handleReset (name) {
        this.errorMsg = '';
        this.$refs[name].resetFields();
        this.user.phone = '';
      },
      open (row) {
        console.log(row)
        this.userId = row.user_id;
        this.user.name = row.name;
        this.user.avatar = row.avatar;
        this.user.phone = row.phone;
        this.user.active = row.active;
        this.showModal = true;
      }
    },
  }
</script>