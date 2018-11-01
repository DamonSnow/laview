<template>
  <div>
    <!-- 封装成组件 -->
    <!--<Modal-->
      <!--v-model="addModal"-->
      <!--title="Title"-->
      <!--:loading="loading">-->
      <!--<p slot="header">新增用户</p>-->
      <!--<p>新增用户使用form表单实现</p>-->
    <!--</Modal>-->
    <!-- 封装成组件 -->
    <Modal
      v-model="authModal"
      title="Title"
      :loading="loading">
      <p slot="header">用户角色</p>
      <p>编辑用户角色的窗口，使用穿梭框实现</p>
    </Modal>
    <!-- 封装成组件 -->
    <create-user :add-user-modal="addUserModal" @on-complete="complete">

    </create-user>
    <!-- 封装成组件 -->
    <Card>
      <p slot="title">
        <Icon type="ios-film-outline"></Icon>
        {{ $t('user-list') }}
      </p>
      <Button @click="addUserModal = true" type="primary" slot="extra">{{ $t('add-user') }}</Button>
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
import createUser from './components/create_user.vue'
export default {
  components: {
      createUser
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
          key: 'job_number',
          sortable: 'custom',
          width: 100
        },
        {
          title: '名称',
          key: 'name',
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
          title: '邮箱',
          key: 'email'
        },
        {
          title: '手机',
          key: 'phone'
        },
        {
            title: '操作',
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
                        this.authModal = true;
                    }
                  }
                },this.$t('auth')),
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
                          this.infoModal = true;
                      }
                  }
              },this.$t('edit'))
              ]);
            },
        }
      ],
      data: [],
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
        console.log(res.data)
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
    }
  },
  mounted: function () {
    this.getData()
  }
}
</script>
