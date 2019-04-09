<template>
  <div>
    <Modal
      ref="formPreview"
      v-model="showModal"
      title="Title"
      fullscreen
      :loading="loading"
      @on-ok="handleSubmit('editFormForm')"
      @on-cancel="handleReset('editFormForm')">
      <p slot="header">{{ row.form_name+'('+row.form_code+') - '+$t('form-preview') }}</p>
      <form-create ref="fc" v-model="fApi" :rule="rule" :option="option"></form-create>
    </Modal>
  </div>
</template>

<script>
  import {maker} from 'form-create'
  import {getFormData, addFormData} from '@/api/form'
  import { string2Obj } from '@/libs/tools'
  export default {
    name: 'form-model',
    props: {
      addModal: Boolean,
    },
    data() {
      return {
        showModal: this.addModal,
        loading: false,
        row: '',
        fApi: {},
        model: {},
        //表单生成规则
        rule: [],
        //组件参数配置
        option: {
            //表单提交事件
            onSubmit: function (formData) {
                alert(JSON.stringify(formData));
            }
        }
      }
    },
    methods: {
      handleSubmit (name) {

      },
      handleReset (name) {

      },
      open (row) {
        let _this = this;
        this.row = row
        this.model = this.fApi.model();
        getFormData(row.id).then(res => {
          this.rule = null;
          this.rule = string2Obj(res.data.data[0])
          this.fApi.refresh()
        })

        _this.showModal = true;
      }
    }

  }

</script>