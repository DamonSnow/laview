<template>
  <div>
    <Modal
      ref="editCategoryModal"
      v-model="showModal"
      title="Title"
      :loading="loading"
      @on-ok="handleSubmit('editCategoryForm')"
      @on-cancel="handleReset('editCategoryForm')">
      <p slot="header">{{ $t('edit-category') }}</p>
      <Form ref="editCategoryForm" :model="category" :rules="rules" :label-width="90">
        <FormItem :label="$t('category_name')" prop="name">
          <Input v-model="category.name"></Input>
        </FormItem>
        <FormItem :label="$t('category_code')" prop="code">
          <Input v-model="category.code"></Input>
        </FormItem>
        <FormItem :label="$t('comment')">
          <Input v-model="category.description"></Input>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
  import { updateCategory } from '@/api/categories'
  export default {
    name: 'edit-category-madel',
    props: {
      addModal: Boolean,
    },
    data() {
      return {
        showModal: this.addModal,
        loading: false,
        category: {
          name: '',
          parent_id: 0,
          code: '',
          description: ''
        },
        categoryId: 0,
        rules: {
          name: [
            { required: true, message: 'The name cannot be empty', trigger: 'blur' }
          ],
          code: [
            { required: true, message: 'The code cannot be empty', trigger: 'blur' }
          ],
        },
      }
    },

    methods: {
      handleSubmit (name) {
        let _this = this;
        _this.$refs[name].validate((valid) => {
          if (valid) {
            updateCategory(_this.categoryId,_this.category).then(res => {
              this.$Message.success('更新分类目录成功');
              _this.$emit('refreshTable',false)
            })
          } else {
            _this.$refs.editCategoryModal.visible = true;
            _this.showModal = true;
          }
        })
      },
      handleReset (name) {
        this.category = {
            name: '',
            parent_id: 0,
            code: '',
            description: ''
        };
      },
      open (row) {
        let _this = this;
        _this.categoryId = row.id;
        _this.category.name = row.name;
        _this.category.code = row.code;
        _this.category.description = row.description;
        _this.showModal = true;
      }
    }

  }
</script>