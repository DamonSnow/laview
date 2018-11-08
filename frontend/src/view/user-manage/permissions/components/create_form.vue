<template>
  <div>
    <Modal
      ref="addPermissionModal"
      v-model="showModal"
      title="Title"
      :loading="loading"
      @on-ok="handleSubmit('permissionForm')"
      @on-cancel="handleReset('permissionForm')">
      <p slot="header">{{ $t('add-permission') }}</p>
      <Form ref="permissionForm" :model="permission" :rules="ruleValidate" :label-width="90">
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
  import { addPermission } from '@/api/permissions'
  export default {
    name: 'user-create-madel',
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

        ruleValidate: {
          name: [
            { required: true, message: 'The name cannot be empty', trigger: 'blur' }
          ],
        },
      }
    },

    methods: {
      handleSubmit (name) {
        let _this = this;

        this.$refs[name].validate((valid) => {
          if (valid) {
            addPermission(this.permission.name, this.permission.comment).then(res => {
              console.log(res.data)
              if(parseInt(res.data.code) === 200) {
                this.$Message.success('新增权限成功');
                _this.$emit('refreshTable',false)
                _this.handleReset('permissionForm')
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                this.showModal =true;
              }
            }).catch(function (error) {
              _this.showModal =true;
              _this.$Message.error(error.response.data.message);
              _this.handleReset('permissionForm')
            })
//
          } else {
            _this.$refs.addPermissionModal.visible = true;
            _this.showModal = true;
          }
        })
      },
      handleReset (name) {
        this.$refs[name].resetFields();
//            _this.showAddModel = false;
//            _this.$emit('showModel',false)
      },
      open () {
        this.showModal = true
      }
    }

  }
</script>