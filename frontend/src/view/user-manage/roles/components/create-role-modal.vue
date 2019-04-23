<template>
  <div>
    <Modal
      ref="addRoleModal"
      v-model="showModal"
      title="Title"
      :loading="loading"
      width="700"
      @on-ok="handleSubmit('roleForm')"
      @on-cancel="handleReset('roleForm')">
      <p slot="header">{{ $t('add-role') }}</p>
      <Form ref="roleForm" :model="role" :rules="ruleValidate" :label-width="90">
        <FormItem :label='$t("role")' prop="name">
          <Input v-model="role.name" :placeholder='$t("Enter role name")'></Input>
        </FormItem>

        <FormItem :label='$t("comment")'>
            <Input v-model="role.comment"></Input>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
  import { roles, addRole } from '@/api/roles'
  import { allPermissions } from '@/api/permissions'
  export default {
    name: 'create-role-madal',
    props: {
      addModal: Boolean,
    },
    data () {
      return {
        showModal: this.addModal,
        loading: false,
        role: {
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
            addRole(_this.role).then(res => {
              if(parseInt(res.data.code) === 200) {
                this.$Message.success('新增角色成功');
                _this.$emit('refreshTable',false)
                _this.handleReset('roleForm')
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                _this.showModal = true;
              }
            }).catch(function (error) {
              _this.showModal = true;
              _this.$Message.error(error.response.data.message);
              _this.handleReset('roleForm')
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
      open () {
        this.showModal = true
      }
    }
  }
</script>
