<template>
  <div>
        <!-- 封装成组件 -->
    <Modal
      v-model="addModal"
      title="Title"
      :loading="loading"
      @on-ok="handleSubmit('permissionForm')"
      @on-cancel="handleReset('permissionForm')">
      <p slot="header">新增权限</p>
        <Form ref="permissionForm" :model="permission" :rules="ruleValidate" :label-width="80">
            <FormItem label="权限" prop="name">
                <Input v-model="permission.name" placeholder="Enter your name"></Input>
            </FormItem>
            <FormItem label="备注">
                <Input v-model="permission.comment" placeholder="备注"></Input>
            </FormItem>
        </Form>
    </Modal>

        <!-- 封装成组件 -->
    <Card>
      <p slot="title">
        <Icon type="ios-film-outline"></Icon>
        {{ $t('permissions-list') }}
      </p>
      <Button @click="addModal = true" type="primary" slot="extra">{{ $t('add-permission') }}</Button>
      <Table :columns="columns" :data="data" :loading="loading" border size="small"></Table>

      <div style="text-align: center;margin: 16px 0">
        <Page
          :total="total"
          :current.sync="current"
          show-sizer
          @on-change="getData"
          @on-page-size-change="handleChangeSize"
        ></Page>
      </div>

    </Card>
  </div>
</template>

<script>
  import { permissions, addPermission } from '@/api/permissions'
  export default {
    data () {
      return {
        columns: [
          {
            type: 'index',
            width: 60,
            align: 'center',
            indexMethod: (row) => {
                return (row._index + 1) + (this.size * this.current) - this.size;
            }
          },
          {
            title: '权限',
            key: 'name',
          },
          {
            title: '备注',
            key: 'comment'
          },
          {
            title: '操作',
            key: 'user_id',
          }
        ],
        data: [],
        loading: false,
        total: 0,
        current: 1,
        size: 10,
        addModal: false,
        sortType: 'normal', //normal asc desc
        permission: {
            name: '',
            comment: ''
        },
        ruleValidate: {
          name: [
            { required: true, message: 'The name cannot be empty', trigger: 'blur' }
          ],
        }
      }
    },
    methods: {
      getData () {
        if (this.loading) return

        this.loading = true

        permissions(this.current, this.size).then(res => {

          this.data = res.data.data;
          this.total = res.data.meta.total;
          this.loading = false;
        })
      },
      handleChangeSize (val) {
        this.size = val;
        this.$nextTick(() => {
          this.getData();
        })
      },
      handleSubmit (name) {
          let _this = this;
          _this.addModal = false;
            this.$refs[name].validate((valid) => {

                if (valid) {
                    addPermission(this.permission.name, this.permission.comment).then(res => {
                        console.log(res.data)
                        if(parseInt(res.data.code) === 200) {
                            this.$Message.success('新增权限成功');
                            this.getData();
                            _this.addModal = false;
                        } else {
                            this.$Message.error(res.data.message);
                            this.addModal =true;
                        }
                    }).catch(function (error) {
                        _this.addModal =true;
                        _this.$Message.error(error.response.data.message);
                        _this.handleReset('permissionForm')
                    })

                } else {
                    _this.addModal = true;
                }
            })
      },
      handleReset (name) {
            this.$refs[name].resetFields();
      }
    },
    mounted: function () {
      this.getData()
    }
  }
</script>
