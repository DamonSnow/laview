<template>
  <div>
    <Modal
      ref="editForm"
      v-model="showModal"
      title="Title"
      :loading="loading"
      @on-ok="handleSubmit('editFormForm')"
      @on-cancel="handleReset('editFormForm')">
      <p slot="header">{{ $t('edit-form-info') }}</p>
      <Form ref="editFormForm" :model="form" :rules="rules" :label-width="120">
        <FormItem :label='$t("Form Code")'>
          <Input v-model="form.form_code" disabled></Input>
        </FormItem>
        <FormItem :label='$t("Form Name")' prop="form_name">
          <Input v-model="form.form_name"></Input>
        </FormItem>
        <FormItem :label='$t("Model")' prop="model">
          <Input v-model="form.model"></Input>
        </FormItem>
        <FormItem :label='$t("comment")' prop="comment">
          <Input v-model="form.comment"></Input>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
  import { updateForm } from '@/api/form'
  export default {
    name: 'edit-form',
    props: {
      addModal: Boolean,
    },
    data() {
      return {
        showModal: this.addModal,
        loading: false,
        form: {
          form_code: '',
          form_name: '',
          model: '',
          comment: '',
        },
        formId: 0,
        rules: {
          form_name: [
            { required: true,  message: 'The item value cannot be empty', trigger: 'blur' },
          ],
        },
      }
    },
    methods: {
      handleSubmit (name) {
        let _this = this;

        _this.$refs[name].validate((valid) => {
          if (valid) {
            updateForm(_this.formId, _this.form).then(res => {
              this.$Message.success('更新表单信息成功');
              _this.$emit('refreshTable', false)
            })
          } else {
            _this.$refs.editForm.visible = true;
            _this.showModal = true;
          }
        })
      },
      handleReset (name) {

      },
      open (row) {
        let _this = this;
        _this.formId = row.id;
        _this.form.form_name = row.form_name;
        _this.form.form_code = row.form_code;
        _this.form.model = row.model;
        _this.form.comment = row.comment;
        _this.showModal = true;
      }
    }

  }
</script>