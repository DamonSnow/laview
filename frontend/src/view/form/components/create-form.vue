<template>
  <div>
    <Modal
      ref="createForm"
      v-model="showModal"
      title="Title"
      :loading="loading"
      width="700"
      @on-ok="handleSubmit('form')"
      @on-cancel="handleReset('form')">
      <p slot="header">{{ $t('add-form') }}</p>
      <Form ref="form" :model="form" :rules="ruleValidate" :label-width="90">
        <FormItem :label='$t("Form Code")' prop="form_code">
          <Input v-model="form.form_code"></Input>
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
  import { addForm } from '@/api/form'
  export default {
    name: 'create-form',
    props: {
      addModal: Boolean,
    },
    data () {
      return {
        showModal: this.addModal,
        loading: false,
        form: {
          form_code: '',
          form_name: '',
          model: '',
          comment: '',
        },
        ruleValidate: {
          form_code: [
            { required: true,  message: 'The item name cannot be empty', trigger: 'blur' },
          ],
          form_name: [
            { required: true,  message: 'The item value cannot be empty', trigger: 'blur' },
          ],
        },
      }
    },
    methods: {
      handleSubmit (name) {
        let _this = this;
        this.$refs[name].validate((valid) => {
          if (valid) {
              addForm(_this.form).then(res => {
              if(parseInt(res.data.code) === 200) {
                this.$Message.success('新增表单成功');
                _this.$emit('refreshTable',false)
                _this.handleReset('form')
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                _this.showModal = true;
              }
            }).catch(function (error) {
              _this.showModal = true;
              _this.$Message.error(error.response.data.message);
              _this.handleReset('form')
            })

          } else {
            //防止验证失败关闭model，需要将model的visible置为true
            _this.$refs.createForm.visible = true;
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
