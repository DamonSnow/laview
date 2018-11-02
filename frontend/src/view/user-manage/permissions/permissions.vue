<template>
  <div>
    <!-- 新增权限的model -->
    <createModel ref="createPermission" :addModal="addModal" @refreshTable="getData"></createModel>
    <!-- 编辑权限的model -->
    <editModel ref="editPermission" :addModal="addModal" @refreshTable="getData"></editModel>

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
  import { permissions, addPermission, deletePermission } from '@/api/permissions'
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
                      this.openEditForm(params.row)//打开编辑页
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
                      let _this = this;
                      deletePermission(params.row.id).then(res => {
                        this.$Message.success('删除权限成功');
                        if(parseInt(params.row._index) === 0 && this.current !== 1) {
                          this.current --;
                        }
                        this.getData();
                      }).catch(function (error) {
                          _this.$Message.error(error.response.data.message);
                      })
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
        this.current = 1;
        this.$nextTick(() => {
          this.getData();
        })
      },
      openCreateForm (val) {
        this.$refs.createPermission.open();
      },
      openEditForm (row) {
          this.$refs.editPermission.open(row);
      },
    },
    mounted: function () {
      this.getData()
    }
  }
</script>
