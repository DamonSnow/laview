<template>
  <div>
    <Modal
      ref="editPermissionModal"
      v-model="showModal"
      title="Title"
      :loading="loading"
      @on-ok="handleSubmit('editPermissionForm')"
      @on-cancel="handleReset('editPermissionForm')">
      <p slot="header">{{ $t('edit-permission') }}</p>
      <Form ref="editPermissionForm" :model="permission" :rules="rules" :label-width="90">
        <FormItem :label="$t('auth')" prop="name">
          <Input v-model="permission.name" :placeholder='$t("Enter Permission name")'></Input>
        </FormItem>
        <FormItem :label="$t('comment')">
          <Input v-model="permission.comment"></Input>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
  import { updatePermission } from '@/api/permissions'
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
      },
      open (row) {
        let _this = this;
        _this.permissionId = row.id;
        _this.permission.name = row.name;
        _this.permission.comment = row.comment;
        _this.showModal = true;
      }
    }

  }
</script>