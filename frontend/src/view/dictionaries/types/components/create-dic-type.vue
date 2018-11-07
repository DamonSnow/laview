<template>
  <div>
    <Modal
      ref="addDicType"
      v-model="showModal"
      title="Title"
      :loading="loading"
      width="700"
      @on-ok="handleSubmit('dicTypeForm')"
      @on-cancel="handleReset('dicTypeForm')">
      <p slot="header">{{ $t('add-dictionary-type') }}</p>
      <Form ref="dicTypeForm" :model="dictionaryType" :rules="ruleValidate" :label-width="120">
        <FormItem :label='$t("dictionary_code")' prop="dic_code">
          <Input v-model="dictionaryType.dic_code" :placeholder='$t("Enter dictionary code")'></Input>
        </FormItem>
        <FormItem :label='$t("dictionary_name")' prop="dic_name">
          <Input v-model="dictionaryType.dic_name" :placeholder='$t("Enter dictionary name")'></Input>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
  import { addDicType } from '@/api/dictionary_type'
  export default {
    name: 'create-dic-type',
    props: {
      addModal: Boolean,
    },
    data () {
      return {
        showModal: this.addModal,
        loading: false,
        dictionaryType: {
          dic_code: '',
          dic_name: '',
        },
        ruleValidate: {
          dic_code: [
            { required: true, message: 'The code cannot be empty', trigger: 'blur' }
          ],
          dic_name: [
            { required: true,  message: 'The name cannot be empty', trigger: 'change' },
          ],
        },
      }
    },
    methods: {
      handleSubmit (name) {
        let _this = this;
        this.$refs[name].validate((valid) => {
          if (valid) {
            addDicType(_this.dictionaryType).then(res => {
              if(parseInt(res.data.code) === 200) {
                this.$Message.success('新增数据字典类型成功');
                _this.$emit('refreshTable',false)
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                _this.showModal = true;
              }
            }).catch(function (error) {
              _this.showModal = true;
              _this.$Message.error(error.response.data.message);
              _this.handleReset('dicTypeForm')
            })

          } else {
            //防止验证失败关闭model，需要将model的visible置为true
            _this.$refs.addDicType.visible = true;
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
