<template>
  <div>
    <Modal
      ref="createDicItem"
      v-model="showModal"
      title="Title"
      :loading="loading"
      width="700"
      @on-ok="handleSubmit('dicItemForm')"
      @on-cancel="handleReset('dicItemForm')">
      <p slot="header">{{ $t('add-dictionary-item') }}</p>
      <Form ref="dicItemForm" :model="dictionaryItem" :rules="ruleValidate" :label-width="90">
        <FormItem :label='$t("dictionary_type")' prop="type_id">
          <Select v-model="dictionaryItem.type_id">
            <Option v-for="dicType in dicTypes" :value="dicType.id" :key="dicType.id">{{ dicType.dic_name }}</Option>
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
  import { addDicItem } from '@/api/dictionary_item'
  export default {
    name: 'create-dic-type',
    props: {
      addModal: Boolean,
      dicTypes: Array,
    },
    data () {
      return {
        showModal: this.addModal,
        loading: false,
        dictionaryItem: {
          type_id: 0,
          item_name: '',
          item_value: '',
          sort: 0,
          comment: '',
        },
        ruleValidate: {
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
        this.$refs[name].validate((valid) => {
          if (valid) {
            addDicItem(_this.dictionaryItem).then(res => {
              if(parseInt(res.data.code) === 200) {
                this.$Message.success('新增数据字典类型成功');
                _this.$emit('refreshTable',false)
                _this.handleReset('dicItemForm')
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                _this.showModal = true;
              }
            }).catch(function (error) {
              _this.showModal = true;
              _this.$Message.error(error.response.data.message);
              _this.handleReset('dicItemForm')
            })

          } else {
            //防止验证失败关闭model，需要将model的visible置为true
            _this.$refs.createDicItem.visible = true;
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
