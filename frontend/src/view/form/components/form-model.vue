<template>
  <div>
    <Modal
      ref="formModel"
      v-model="showModal"
      title="Title"
      fullscreen
      :loading="loading"
      @on-ok="handleSubmit('editFormForm')"
      @on-cancel="handleReset('editFormForm')">
      <p slot="header">{{ $t('edit-form-model') + ' - ' + rowData.form_name+'('+rowData.form_code+')' }}</p>
      <Row>
        <Col span="10">
        <Card>
          <p slot="title">
            <Icon type="md-aperture"/>
            Json规则
            <small>详见<a target="_blank" href="http://www.form-create.com/">form-create</a></small>
          </p>
          <!--<json-editor :onChange="onChange" :json="jsonData" />-->
          <Form ref="form" :label-width="90">
            <FormItem :label='$t("version")'>
              <InputNumber :max="99" :min="0" v-model="rowData.version"></InputNumber>
            </FormItem>
            <FormItem label='Json'>
              <Input type="textarea" v-model="jsonData" :rows="30" @click="applyJson"></Input>
            </FormItem>

          </Form>

          <Row style="margin-top: 10px;">
            <Col span="8" offset="16">
            <Button type="primary" @click="applyJson">Apply</Button>
            <Button type="primary" style="margin-left: 10px;" @click="saveJson">Save</Button>
            </Col>
          </Row>

        </Card>
        </Col>
        <Col span="14">
        <Card>
          <div id="form-create">
            <form-create ref="fc" v-model="fApi" :rule="rule" :option="option"></form-create>
          </div>
        </Card>

        </Col>
      </Row>
    </Modal>
  </div>
</template>

<script>
  import {maker} from 'form-create'
  import JsonEditor from '_c/json-editor'
  import {getFormData, saveFormData} from '@/api/form'
  import { obj2String, string2Obj } from '@/libs/tools'
  export default {
    name: 'form-model',
    props: {
      addModal: Boolean,
    },
    data() {
      return {
        showModal: this.addModal,
        loading: false,
        rowData: {
            form_code: '',
            form_name: '',
            model: '',
            version: 0,
            comment: '',
        },
        jsonData: '',
        tempData: [],
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
    mounted: function () {
      this.model = this.fApi.model();
    },
    methods: {
      handleSubmit (name) {

      },
      handleReset (name) {

      },
      onChange(newJson) {
        // handle json changes
        console.log(newJson)
        this.tempData = newJson;
      },
      applyJson() {
        this.rule = null;
        this.tempData = this.jsonData
        if(typeof this.tempData === 'undefined' || this.tempData.length <= 0) {
            this.rule = [];
        } else {
            this.rule = string2Obj(this.tempData);
        }
        this.fApi.refresh()

      },
      saveJson() {

        saveFormData(this.rowData.id, this.rowData.version, {data: this.tempData}).then(res => {
          if (parseInt(res.data.code)) {
            this.$Message.success('保存表单模板成功');
          } else {
            this.$Message.error(res.data.message);
          }
        }).catch(function (error) {
          this.$Message.error(error.response.data.message);
        });

      },
      open (row) {
//        this.jsonData = '';
//        this.tempData = [];
//        this.rule = [];
//        this.fApi.refresh()
//        this.rowData = row;
//        this.model = this.fApi.model();
          this.rowData = row;
        if(parseInt(row.version) > 0) {
          getFormData(row.id).then(res => {
            this.jsonData = res.data.data[0]
            this.tempData = this.jsonData;
            this.applyJson();
          })
        } else {
            this.jsonData = '[]';
            this.tempData = this.jsonData;
            this.applyJson();
        }
        this.showModal = true;
      }
    }

  }

</script>