<template>
  <div>
    <Modal
      ref="createOrgForm"
      v-model="showModal"
      title="Title"
      :loading="loading"
      @on-ok="handleSubmit('orgForm')"
      @on-cancel="handleReset('orgForm')">
      <p slot="header">{{ $t('add-org') }}</p>
      <Form ref="orgForm" :model="org" :rules="ruleValidate" :label-width="90">
        <FormItem :label="$t('org-label')" prop="label">
          <Input v-model="org.label" :placeholder='$t("Enter Branch name")'></Input>
        </FormItem>
        <FormItem :label="$t('org-code')" prop="code">
          <Input v-model="org.code" :placeholder='$t("Enter Branch code")'></Input>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
  import {addBranch} from '@/api/branches'
  export default {
    name: 'org-create-madel',
    props: {
      addModal: Boolean,
    },
    data() {
      return {
        showModal: this.addModal,
        loading: false,
        org: {
          parent_id: 0,
          label: '',
          code: ''
        },

        ruleValidate: {
          label: [
            {required: true, message: 'The branch name cannot be empty', trigger: 'blur'}
          ],
          code: [
            {required: true, message: 'The branch code cannot be empty', trigger: 'blur'}
          ],
        },
      }
    },

    methods: {
      handleSubmit (name) {
        let _this = this;
        this.$refs[name].validate((valid) => {
          if (valid) {

            addBranch(_this.org).then(res => {

              if (parseInt(res.data.code) === 200) {
                this.$Message.success('新增部门成功');
                _this.$emit('refreshTable', false)
                _this.handleReset('orgForm')
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                this.showModal = true;
              }
            }).catch(function (error) {
              _this.showModal = true;
              _this.$Message.error(error.response.data.message);
              _this.handleReset('orgForm')
            })
          } else {
            _this.$refs.createOrgForm.visible = true;
            _this.showModal = true;
          }
        })
      },
      handleReset (name) {
        this.$refs[name].resetFields();
      },
      open (parent_id) {
        this.org.parent_id = parent_id;
        this.showModal = true;
      }
    }

  }
</script>