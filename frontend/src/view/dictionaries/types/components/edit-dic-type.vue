<template>
  <div>
    <Modal
      ref="editDicType"
      v-model="showModal"
      title="Title"
      :loading="loading"
      @on-ok="handleSubmit('editDicTypeForm')"
      @on-cancel="handleReset('editDicTypeForm')">
      <p slot="header">{{ $t('edit-dictionary-type') }}</p>
      <Form ref="editDicTypeForm" :model="dictionaryType" :rules="rules" :label-width="120">
        <FormItem :label="$t('auth')" prop="dic_name">
          <Input v-model="dictionaryType.dic_name" :placeholder='$t("Enter dictionary code")'></Input>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
  import { updateDicType } from '@/api/dictionary_type'
  export default {
    name: 'edit-dic-type',
    props: {
      addModal: Boolean,
    },
    data() {
      return {
        showModal: this.addModal,
        loading: false,
        dictionaryType: {
          dic_name: '',
        },
        dictionaryTypeId: 0,
        rules: {
          dic_name: [
            {required: true, message: 'The name cannot be empty', trigger: 'blur'}
          ],
        },
      }
    },
    methods: {
      handleSubmit (name) {
        let _this = this;

        _this.$refs[name].validate((valid) => {
          if (valid) {
            updateDicType(_this.dictionaryTypeId, _this.dictionaryType).then(res => {
              this.$Message.success('更新数据字典类型成功');
              _this.$emit('refreshTable', false)
            })
          } else {
            _this.$refs.editDicType.visible = true;
            _this.showModal = true;
          }
        })
      },
      handleReset (name) {
        this.$refs[name].resetFields();
      },
      open (row) {
        let _this = this;
        _this.dictionaryTypeId = row.id;
        _this.dictionaryType.dic_name = row.dic_name;
        _this.showModal = true;
      }
    }

  }
</script>