<template>
  <div>
    <Modal
      ref="editTag"
      v-model="showModal"
      title="Title"
      :loading="loading"
      @on-ok="handleSubmit('editTagForm')"
      @on-cancel="handleReset('editTagForm')">
      <p slot="header">{{ $t('edit-tag') }}</p>
      <Form ref="editTagForm" :model="tag" :rules="rules" :label-width="120">
        <FormItem :label="$t('tag_name')" prop="name">
          <Input v-model="tag.name"></Input>
        </FormItem>
        <FormItem :label="$t('tag_slug')" prop="slug">
          <Input v-model="tag.slug"></Input>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
  import { updTag } from '@/api/tags'
  export default {
    name: 'edit-dic-type',
    props: {
      addModal: Boolean,
    },
    data() {
      return {
        showModal: this.addModal,
        loading: false,
        tag: {
          name: '',
          slug: '',
        },
        tagId: 0,
        rules: {
          name: [
            {required: true, message: 'The name cannot be empty', trigger: 'blur'}
          ],
          slug: [
            {required: true, message: 'The slug cannot be empty', trigger: 'blur'}
          ],
        },
      }
    },
    methods: {
      handleSubmit (name) {
        let _this = this;

        _this.$refs[name].validate((valid) => {
          if (valid) {
            updTag(_this.tagId, _this.tag).then(res => {
              this.$Message.success('更新数据字典类型成功');
              _this.$emit('refreshTable', false)
            })
          } else {
            _this.$refs.editTag.visible = true;
            _this.showModal = true;
          }
        })
      },
      handleReset (name) {
        this.$refs[name].resetFields();
      },
      open (row) {
        let _this = this;
        _this.tagId = row.id;
        _this.tag.name = row.name;
        _this.tag.slug = row.slug;
        _this.showModal = true;
      }
    }

  }
</script>