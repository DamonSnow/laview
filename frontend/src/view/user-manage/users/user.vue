<template>
  <div>
    <!-- 新增用户modal -->
    <createUser ref="createUser" :addUserModal="addUserModal" @refreshTable="getData"></createUser>
    <!-- 编辑用户modal -->
    <editUser ref="editUser"  @refreshTable="getData"></editUser>
    <!-- 编辑用户角色modal -->
    <editUserRole ref="editUserRole" :allRoles="allRoles" :addUserModal="addUserModal" @refreshTable="getData"></editUserRole>
    <!-- 封装成组件 -->
    <Card>
      <p slot="title">
        <Icon type="ios-film-outline"></Icon>
        {{ $t('user-list') }}
      </p>
      <Button @click="openCreateForm" type="primary" slot="extra">{{ $t('add-user') }}</Button>
      <Table :columns="columns" :data="data" stripe :loading="loading" border size="small" @on-sort-change="handleSortChange"></Table>

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
import { users } from '@/api/user'
import { allRoles } from '@/api/roles'
import createUser from './components/create_user.vue'
import editUser from './components/edit-user-modal.vue'
import editUserRole from './components/edit-user-role-modal.vue'
export default {
  components: {
    createUser,
    editUser,
    editUserRole
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
          title: '工号',
          align: 'center',
          key: 'job_number',
          sortable: 'custom',
          width: 100
        },
        {
          title: '名称',
          key: 'name',
          align: 'center',
          render: (h, params) => {

            return h('div',[
              h('Avatar', {
                props: {
                  src : params.row.avatar
                }
              }),
              h('span',params.row.name)
            ]);
          },
        },
        {
          title: 'Active',
          key: 'active',
          align: 'center',
          width: 100,
          render: (h, params) => {
            let color = '';
            let text = '';
            if(parseInt(params.row.active) === 1) {
              color = 'success';
              text = '激活'
            } else {
              color = 'error';
              text = '未激活'
            }
            return h('Tag',{
              props: {
                color: color
              }
            },text);
          },
        },
        {
          title: '角色',
          key: 'roles',
          align: 'center',
          render: (h, params) => {
            if(params.row.roles.length > 0) {
              let cols = params.row.roles.map(item => {
                return h('Tag', {
                  props: {
                    color: 'success'
                  }
                }, item);
              })
              return h('div', cols)
            }

          }
        },
        {
          title: '邮箱',
          align: 'center',
          key: 'email'
        },
        {
          title: '手机',
          align: 'center',
          key: 'phone'
        },
        {
            title: '操作',
            align: 'center',
            key: 'user_id',
            render: (h, params) => {
              return h('div',[
                h('Button', {
                  props: {
                    type : 'info',
                    size: 'small'
                  },
                  on: {
                    click: () => {
                      this.openEditUserRoleForm(params.row)
                    }
                  }
                },this.$t('role')),
                h('Button',{
                  props: {
                    type : 'info',
                    size: 'small'
                  },
                  style: {
                    margin: '0 0 0 5px'
                  },
                  on: {
                    click: () => {
                      this.openEditUserForm(params.row)
                    }
                  }
              },this.$t('edit'))
              ]);
            },
        }
      ],
      data: [],
      allRoles: [],
      loading: false,
      total: 0,
      current: 1,
      size: 10,
      infoModal: false,
      authModal: false,
      addUserModal: false,
      sortType: 'normal', //normal asc desc
    }
  },
  methods: {
    getData () {
      if (this.loading) return

      this.loading = true

      users(this.current, this.size, this.sortType).then(res => {

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
    handleSortChange ({ columns, key, order }) {
      this.sortType = order;
      this.current = 1;
      this.getData();
    },
    complete (e) {
        this.addUserModal = e;
    },
    openCreateForm () {
      this.$refs.createUser.open();
    },
    openEditUserForm (row) {
      this.$refs.editUser.open(row);
    },
    openEditUserRoleForm (row) {
      this.$refs.editUserRole.open(row);
    }
  },
  mounted: function () {

    this.getData()
    //获取所有角色
    allRoles().then(res => {
        let index = 0;
        for (let i in res.data.data) {
            this.allRoles[index] = [];
            this.allRoles[index]['key'] = res.data.data[i];
            this.allRoles[index]['label'] = res.data.data[i];
            this.allRoles[index]['disable'] = false;
            index++;
        }
    })
  }
}
</script>
