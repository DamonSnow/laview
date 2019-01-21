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
        <Icon type="ios-person"></Icon>
        {{ $t('user-list') }}
      </p>
      <Button @click="openCreateForm" type="primary" slot="extra">{{ $t('add-user') }}</Button>
      <Row type="flex" justify="end" class="code-row-bg" :gutter="16">
        <Col span="3">
          <Input icon="search" placeholder="请输入工号搜索..." v-model="search.job_number"/>
        </Col>
        <Col span="3">
          <Input icon="search" placeholder="请输入邮箱搜索..." v-model="search.email"/>
        </Col>
        <Col span="2">
          <Button type="primary" icon="ios-search" @click="getData()">Search</Button>
        </Col>
      </Row>
      <br>
      <Row>
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
      </Row>


    </Card>
  </div>
</template>

<script>
import { users } from '@/api/user'
import { allRoles } from '@/api/roles'
import createUser from './components/create_user.vue'
import editUser from './components/edit-user-modal.vue'
import editUserRole from './components/edit-user-role-modal.vue'
import defAvatar from '@/assets/images/avatars/avatar.png'
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
          indexMethod: (row) => {
            return (row._index + 1) + (this.size * this.current) - this.size;
          }
        },
        {
          title: this.$t('job number'),
          key: 'job_number',
          sortable: 'custom',
          width: 100
        },
        {
          title: this.$t('name'),
          key: 'name',
          render: (h, params) => {

            return h('div',[
              h('Avatar', {
                props: {
                  src : params.row.avatar ? params.row.avatar : defAvatar,
                  icon: 'ios-person'
                }
              }),
              h('span',params.row.name)
            ]);
          },
        },
        {
          title: 'active',
          key: 'active',
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
          title: this.$t('role'),
          key: 'roles',
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
          title: this.$t('e-mail'),
          key: 'email'
        },
        {
          title: this.$t('phone'),
          key: 'phone'
        },
        {
            title: this.$t('operation'),
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
      search: {},
    }
  },
  methods: {
    getData () {
      if (this.loading) return
        let search = [];
      this.loading = true
      if(JSON.stringify(this.search)) {
        search =JSON.stringify(this.search)
      }
      users(this.current, this.size, this.sortType, search).then(res => {

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
