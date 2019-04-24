<template>
  <div>
    <Modal
      ref="addTag"
      v-model="showModal"
      title="Title"
      :loading="loading"
      width="700"
      @on-ok="handleSubmit('tagForm')"
      @on-cancel="handleReset('tagForm')">
      <p slot="header">{{ $t('add-tag') }}</p>
      <Form ref="tagForm" :model="tag" :rules="ruleValidate" :label-width="120">
        <FormItem :label='$t("tag_name")' prop="name">
          <Input v-model="tag.name"></Input>
        </FormItem>
        <FormItem :label='$t("tag_slug")' prop="slug">
          <Input v-model="tag.slug"></Input>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
  import { addTag } from '@/api/tags'
  export default {
    name: 'add-tag',
    props: {
      addModal: Boolean,
    },
    data () {
      return {
        showModal: this.addModal,
        loading: false,
        tag: {
          name: '',
          slug: '',
        },
        ruleValidate: {
          name: [
            { required: true, message: 'The name cannot be empty', trigger: 'blur' }
          ],
          slug: [
            { required: true,  message: 'The slug cannot be empty', trigger: 'blur' },
          ],
        },
      }
    },
    methods: {
      handleSubmit (name) {
        let _this = this;
        this.$refs[name].validate((valid) => {
          if (valid) {
            addTag(_this.tag).then(res => {
              if(parseInt(res.data.code) === 200) {
                this.$Message.success('新增标签成功');
                _this.$emit('refreshTable',false)
                _this.handleReset('tagForm')
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                _this.showModal = true;
              }
            }).catch(function (error) {
              _this.showModal = true;
              _this.$Message.error(error.response.data.message);
              _this.handleReset('tagForm')
            })

          } else {
            //防止验证失败关闭model，需要将model的visible置为true
            _this.$refs.addTag.visible = true;
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
