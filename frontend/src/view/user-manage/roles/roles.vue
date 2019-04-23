<template>
  <div>
    <!-- 新增角色modal -->
    <createRoleModal ref="createRole" :addModal="addModal" @refreshTable="getData"></createRoleModal>
    <!-- 编辑角色modal -->
    <editRoleModal ref="editRole" :addModal="addModal" @refreshTable="getData"></editRoleModal>
    <DrawerPage v-model="isOpenDrawerPage" @refreshTable="getData" :role="editRole"/>
    <Card>
      <p slot="title">
        <Icon type="md-card"></Icon>
        {{ $t('roles-list') }}
      </p>
      <Button @click="openCreateForm" type="primary" slot="extra">{{ $t('add-role') }}</Button>
      <Table :columns="columns" stripe :data="data" :loading="loading" border size="small"></Table>

      <div style="text-align: center;margin: 16px 0">
        <Page
          :total="total"
          :current.sync="current"
          show-sizer
          show-total
          show-elevator
          @on-change="getData"
          @on-page-size-change="handleChangeSize"
        ></Page>
      </div>

    </Card>
  </div>
</template>

<script>
  import { roles, addRole, deleteRole } from '@/api/roles'
  import { allPermissions } from '@/api/permissions'
  import createRoleModal from './components/create-role-modal.vue'
  import editRoleModal from './components/edit-role-modal.vue'
  import DrawerPage from './components/drawer.vue'
  export default {
    components: {
      createRoleModal,
      editRoleModal,
      DrawerPage
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
            title: this.$t('role'),
            key: 'name',
          },
          {
            title: this.$t('comment'),
            key: 'comment'
          },
          {
            title: this.$t('operation'),
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
                        this.editRole = params.row
                        this.isOpenDrawerPage = true;
                      }
                    }
                },this.$t('auth')),
                h('Button', {
                  props: {
                    type : 'info',
                    size: 'small'
                  },
                  style: {
                    margin: '0 0 0 5px'
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
                      deleteRole(params.row.id).then(res => {
                        this.$Message.success('删除role成功');
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
        isOpenDrawerPage: false,
        editRole: {
            id: 0,
            name: ''
        },
        rights: [],

      }
    },
    methods: {
      getData () {
        if (this.loading) return

        this.loading = true

        roles(this.current, this.size).then(res => {
          this.data = res.data.data;
          this.total = res.data.meta.total;
          this.loading = false;
        })
      },
      getPermissions () {
        allPermissions().then(res => {
          this.rights = res.data.data.reduce(function (per, item, index) {
            per[index] = [];
            per[index]['key'] = item['id'];
            per[index]['label'] = item['name'];
            per[index]['disable'] = false;
            return per;
          },[]);
        })
      },
      handleChangeSize (val) {
        this.size = val;
        this.$nextTick(() => {
          this.getData();
        })
      },
      openCreateForm () {
        this.$refs.createRole.open();
      },
      openEditForm (row) {
        this.$refs.editRole.open(row);
      }
    },
    mounted: function () {
      this.getData();
      this.getPermissions();
    }
  }
</script>
