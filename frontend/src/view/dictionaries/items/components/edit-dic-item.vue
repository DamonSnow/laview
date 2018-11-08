<template>
  <div>
    <Modal
      ref="editDicItem"
      v-model="showModal"
      title="Title"
      :loading="loading"
      @on-ok="handleSubmit('editDicItemForm')"
      @on-cancel="handleReset('editDicItemForm')">
      <p slot="header">{{ $t('edit-dictionary-item') }}</p>
      <Form ref="editDicItemForm" :model="dictionaryItem" :rules="rules" :label-width="120">
        <FormItem :label='$t("dictionary_type")' prop="type_id">
          <Select v-model="dictionaryItem.type_id">
            <Option v-for="dicType in dicTypes" :value='dicType.id' :key='dicType.id'>{{ dicType.dic_name }}</Option>
          </Select>
        </FormItem>
        <FormItem :label='$t("item_name")' prop="item_name">
          <Input v-model="dictionaryItem.item_name" :placeholder='$t("Enter dictionary item name")'></Input>
        </FormItem>
        <FormItem :label='$t("item_value")' prop="item_value">
          <Input v-model="dictionaryItem.item_value" :placeholder='$t("Enter dictionary item value")'></Input>
        </FormItem>
        <FormItem :label='$t("sort")' prop="sort">
          <InputNumber v-model="dictionaryItem.sort"></InputNumber>
        </FormItem>
        <FormItem :label='$t("comment")' prop="comment">
          <Input v-model="dictionaryItem.comment"></Input>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
  import { updateDicItem } from '@/api/dictionary_item'
  export default {
    name: 'edit-dic-item',
    props: {
      addModal: Boolean,
      dicTypes: Array,
    },
    data() {
      return {
        showModal: this.addModal,
        loading: false,
        dictionaryItem: {
          type_id: '',
          item_name: '',
          item_value: '',
          sort: 1,
          comment: '',
        },
        dictionaryItemId: 0,
        rules: {
          type_id: [
            { required: true, message: 'The dictionary type cannot be empty', trigger: 'change' }
          ],
          item_name: [
            { required: true,  message: 'The item name cannot be empty', trigger: 'blur' },
          ],
          item_value: [
            { required: true,  message: 'The item value cannot be empty', trigger: 'blur' },
          ],
          sort: [
            { required: true, type: 'number', min:1, message: 'The sort cannot be empty', trigger: 'blur' },
          ],
        },
      }
    },
    methods: {
      handleSubmit (name) {
        let _this = this;

        _this.$refs[name].validate((valid) => {
          if (valid) {
            updateDicItem(_this.dictionaryItemId, _this.dictionaryItem).then(res => {
              this.$Message.success('更新数据字典项目成功');
              _this.$emit('refreshTable', false)
            })
          } else {
            _this.$refs.editDicItem.visible = true;
            _this.showModal = true;
          }
        })
      },
      handleReset (name) {
        this.$refs[name].resetFields();
      },
      open (row) {
        let _this = this;
        _this.dictionaryItemId = row.id;
        _this.dictionaryItem.type_id = row.type_id.toString();
        _this.dictionaryItem.item_name = row.item_name;
        _this.dictionaryItem.item_value = row.item_value;
        _this.dictionaryItem.sort = parseInt(row.sort);
        _this.dictionaryItem.comment = row.comment;
        _this.showModal = true;
      }
    }

  }
</script>