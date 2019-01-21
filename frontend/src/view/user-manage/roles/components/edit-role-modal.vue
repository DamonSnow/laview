<template>
  <div>
    <Modal
      ref="editRoleModal"
      v-model="showModal"
      title="Title"
      :loading="loading"
      width="700"
      @on-ok="handleSubmit('editRoleForm')"
      @on-cancel="handleReset('editRoleForm')">
      <p slot="header">{{ $t('edit-role') }}</p>
      <Form ref="editRoleForm" :model="role" :rules="ruleValidate" :label-width="90">
        <FormItem :label='$t("role")' prop="name">
          <Input v-model="role.name" :placeholder='$t("Enter role name")'></Input>
        </FormItem>
        <FormItem :label='$t("auth")' prop="permissions">
          <Transfer
            :data="rights"
            :target-keys="role.permissions"
            :render-format="permission_rendor"
            @on-change="handlePermission"></Transfer>
        </FormItem>
        <FormItem :label='$t("comment")'>
          <Input v-model="role.comment"></Input>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
  import { addRole, updateRole } from '@/api/roles'
  export default {
    name: 'create-role-madal',
    props: {
      addModal: Boolean,
      rights: Array
    },
    data () {
      return {
        showModal: this.addModal,
        loading: false,
        roleId: 0,
        role: {
          name: '',
          permissions: [],
          comment: ''
        },
        ruleValidate: {
          name: [
            { required: true, message: 'The name cannot be empty', trigger: 'blur' }
          ],
          permissions: [
            { required: true, type: 'array', min: 1, message: 'Choose at least one hobby', trigger: 'change' },
          ],
        },
      }
    },
    methods: {
      handleSubmit (name) {
        let _this = this;
        this.$refs[name].validate((valid) => {
          if (valid) {
            updateRole(_this.roleId, _this.role.name, _this.role.permissions, _this.role.comment).then(res => {
              if(parseInt(res.data.code) === 200) {
                this.$Message.success('更新权限成功');
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
            _this.$refs.addRoleModal.visible = true;
            _this.showModal = true;
          }
        })
      },
      handleReset (name) {
        this.$refs[name].resetFields();
      },
      handlePermission (newTargetKeys, direction, moveKeys) {

        this.role.permissions = newTargetKeys;
      },
      permission_rendor (item) {
        return item.label;
      },
      open (row) {
        this.roleId = row.id;
        this.role.name = row.name;
        this.role.permissions = row.permissions.map(permission => {
          return permission.id;
        });
        this.role.comment = row.comment;
        this.showModal = true
      }
    }
  }
</script>
