<template>
  <div>
        <!-- 封装成组件 -->
    <!--<Modal-->
      <!--ref="addRoleModal"-->
      <!--v-model="addModal"-->
      <!--title="Title"-->
      <!--:loading="loading"-->
      <!--width="700"-->
      <!--@on-ok="handleSubmit('roleForm')"-->
      <!--@on-cancel="handleReset('roleForm')">-->
      <!--<p slot="header">{{ $t('add-role') }}</p>-->
        <!--<Form ref="roleForm" :model="role" :rules="ruleValidate" :label-width="80">-->
            <!--<FormItem label="角色" prop="name">-->
                <!--<Input v-model="role.name" :placeholder='$t("Enter role name")'></Input>-->
            <!--</FormItem>-->
            <!--<FormItem label="权限" prop="permissions">-->
                <!--<Transfer-->
                    <!--:data="rights"-->
                    <!--:target-keys="role.permissions"-->
                    <!--:render-format="permission_rendor"-->
                    <!--@on-change="handlePermission"></Transfer>-->
            <!--</FormItem>-->
            <!--<FormItem label="备注">-->
                <!--<Input v-model="role.comment" placeholder="备注"></Input>-->
            <!--</FormItem>-->
        <!--</Form>-->
    <!--</Modal>-->
      <createRoleModal ref="createRole" :addModal="addModal" :rights="rights" @refreshTable="getData"></createRoleModal>
      <editRoleModal ref="editRole" :addModal="addModal" :rights="rights" @refreshTable="getData"></editRoleModal>

        <!-- 封装成组件 -->
    <Card>
      <p slot="title">
        <Icon type="ios-film-outline"></Icon>
        {{ $t('permissions-list') }}
      </p>
      <Button @click="openCreateForm" type="primary" slot="extra">{{ $t('add-role') }}</Button>
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
  import { roles, addRole, deleteRole } from '@/api/roles'
  import { allPermissions } from '@/api/permissions'
  import createRoleModal from './components/create-role-modal.vue'
  import editRoleModal from './components/edit-role-modal.vue'
  export default {
    components: {
      createRoleModal,
      editRoleModal
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
            title: '角色',
            key: 'name',
          },
          {
            title: '权限',
            key: 'permissions',
            render: (h, params) => {
              console.log(params.row.permissions)
                if(params.row.permissions.length > 0) {
                    let cols = params.row.permissions.map(item => {

                        return h('Tag', {
                            props: {
                                color: 'success'
                            }
                        }, item.name);
                    })
                    return h('div', cols)
                }

            }
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
        rights: [],

      }
    },
    methods: {
      getData () {
        if (this.loading) return

        this.loading = true

        roles(this.current, this.size).then(res => {
          console.log(res.data)
          this.data = res.data.data;
          this.total = res.data.meta.total;
          this.loading = false;
        })
      },
      getPermissions () {
          allPermissions(this.current, this.size).then(res => {
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
