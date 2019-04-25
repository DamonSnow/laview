<template>
  <div>
    <Modal
      ref="addCategoryModal"
      v-model="showModal"
      title="Title"
      :loading="loading"
      @on-ok="handleSubmit('categoryForm')"
      @on-cancel="handleReset('categoryForm')">
      <p slot="header">{{ $t('add-category') }}</p>
      <Form ref="categoryForm" :model="category" :rules="ruleValidate" :label-width="90">
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
  import { addCategory } from '@/api/categories'
  export default {
    name: 'add-category-madel',
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

        ruleValidate: {
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

        this.$refs[name].validate((valid) => {
          if (valid) {
            addCategory(this.category).then(res => {
              if(parseInt(res.data.code) === 200) {
                this.$Message.success('新增分类目录成功');
                _this.$emit('refreshTable',false)
                _this.handleReset('categoryForm')
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                this.showModal =true;
              }
            }).catch(function (error) {
              _this.showModal =true;
              _this.$Message.error(error.response.data.message);
              _this.handleReset('categoryForm')
            })
//
          } else {
            _this.$refs.addCategoryModal.visible = true;
            _this.showModal = true;
          }
        })
      },
      handleReset (name) {
        this.$refs[name].resetFields();
//            _this.showAddModel = false;
//            _this.$emit('showModel',false)
      },
      open (parent_id) {
        this.category.parent_id = parent_id;
        this.showModal = true
      }
    }

  }
</script>