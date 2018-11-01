<template>
  <div>
        <!-- 封装成组件 -->
    <!--<Modal-->
      <!--ref="addPermissionModal"-->
      <!--v-model="addModal"-->
      <!--title="Title"-->
      <!--:loading="loading"-->
      <!--@on-ok="handleSubmit('permissionForm')"-->
      <!--@on-cancel="handleReset('permissionForm')">-->
      <!--<p slot="header">新增权限</p>-->
        <!--<Form ref="permissionForm" :model="permission" :rules="ruleValidate" :label-width="80">-->
            <!--<FormItem label="权限" prop="name">-->
                <!--<Input v-model="permission.name" :placeholder='$t("Enter Permission name")'></Input>-->
            <!--</FormItem>-->
            <!--<FormItem label="备注">-->
                <!--<Input v-model="permission.comment"></Input>-->
            <!--</FormItem>-->
        <!--</Form>-->
    <!--</Modal>-->
    <createModel ref="createPermission" :addModal="addModal" @refreshTable="getData"></createModel>
    <editModel ref="editPermission" :addModal="addModal" @refreshTable="getData"></editModel>
        <!-- 封装成组件 -->
    <Card>
      <p slot="title">
        <Icon type="ios-film-outline"></Icon>
        {{ $t('permissions-list') }}
      </p>
      <Button @click="openCreateForm" type="primary" slot="extra">{{ $t('add-permission') }}</Button>
      <Table :columns="columns" stripe :data="data" :loading="loading" border size="small"></Table>

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
  import createModel from './components/create_form.vue'
  import editModel from './components/edit-form.vue'
  export default {
    components: {
      createModel,
      editModel
    },
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
            key: 'id',
            render: (h, params) => {
              return h('div',[
                h('Button', {
                  props: {
                    type : 'info',
                    size: 'small'
                  },
                  on: {
                    click: () => {
                      this.openEditForm(params.row.id)//打开编辑页
                    }
                  }
                },this.$t('edit')),
                h('Poptip',{
                  props: {
                    confirm: true,
                    title: '确认要删除该权限吗?'
                  },
                  style: {
                    margin: '0 0 0 5px'
                  },
                  on: {
                    'on-ok': () => {
                      this.$Message.info('删除role');
                    }
                  }
                },[
                    h('Button',{
                        props: {
                            type : 'error',
                            size: 'small'
                        },
                    },this.$t('delete'))
                ],this.$t('delete'))
              ]);
            },
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
      openCreateForm (val) {
        this.$refs.createPermission.open();
      },
      openEditForm (id) {
          this.$refs.editPermission.open(id);
      },
//      handleSubmit (name) {
//          let _this = this;
//          _this.addModal = false;
//            this.$refs[name].validate((valid) => {
//
//                if (valid) {
//                    addPermission(this.permission.name, this.permission.comment).then(res => {
//                        console.log(res.data)
//                        if(parseInt(res.data.code) === 200) {
//                            this.$Message.success('新增权限成功');
//                            this.getData();
//                            _this.addModal = false;
//                        } else {
//                            this.$Message.error(res.data.message);
//                            this.addModal =true;
//                        }
//                    }).catch(function (error) {
//                        _this.addModal =true;
//                        _this.$Message.error(error.response.data.message);
//                        _this.handleReset('permissionForm')
//                    })
//
//                } else {
//                    _this.$refs.addPermissionModal.visible = true;
//                    _this.addModal = true;
//                }
//            })
//      },
//      handleReset (name) {
//            this.$refs[name].resetFields();
//      }
    },
    mounted: function () {
      this.getData()
    }
  }
</script>
