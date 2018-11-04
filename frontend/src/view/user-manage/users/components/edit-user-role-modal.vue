<template>
  <div>
    <Modal
      ref="editUserRoleModal"
      v-model="showModal"
      title="Title"
      :loading="loading"
      width="700"
      @on-ok="handleSubmit('editUserRoleForm')"
      @on-cancel="handleReset('editUserRoleForm')">
      <p slot="header">编辑用户角色</p>
      <Form ref="editUserRoleForm" :model="roles" :rules="ruleValidate" :label-width="80">
        <FormItem label="角色" prop="tagetRoles">
          <Transfer
            :data="allRoles"
            :target-keys="roles.tagetRoles"
            :render-format="role_rendor"
            @on-change="handleRoles"></Transfer>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>
<script>
  import { updateUserRole } from '@/api/user'
  export default {
    name: 'edit-user-role-modal',
    props : {
      allRoles: Array,
      editModal : Boolean,
    },
    data () {
      return {
        showModal: this.editModal,
        loading: false,
        userId: 0,
        roles: {
          tagetRoles: []
        },
        ruleValidate: {
          'roles.tagetRoles': [
            { required: true, message: 'The roles cannot be empty', trigger: 'blur' }
          ],
        }
      }
    },

    methods: {
      close () {
        this.showModal = false;
      },
      handleRoles (newTargetKeys, direction, moveKeys) {
        this.roles.tagetRoles = newTargetKeys;
      },
      handleSubmit (name) {
        let _this = this;
        this.$refs[name].validate((valid) => {
          if (valid) {console.log(_this.roles.tagetRoles)
            updateUserRole(_this.userId, _this.roles.tagetRoles).then(res => {
              if(parseInt(res.data.code) === 200) {
                this.$Message.success('更新用户角色成功');
                _this.$emit('refreshTable',false)
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                _this.showModal = true;
              }
            }).catch(function (error) {
              _this.showModal = true;
              _this.$Message.error(error.response.data.message);
              _this.handleReset('editRoleForm')
            })
          } else {
            //防止验证失败关闭model，需要将model的visible置为true
            _this.$refs.editUserRoleModal.visible = true;
            _this.showModal = true;
          }
        })
      },
      handleReset (name) {
        this.$refs[name].resetFields();
      },
      role_rendor (item) {
        return item.label;
      },
      open (row) {
        this.userId = row.user_id;
        this.roles.tagetRoles = row.roles;
        this.showModal = true;
      }
    },
    mounted: function() {
      console.log(this.allRoles)
    }
  }
</script>