<template>
  <div>
    <!-- 新增字典项目modal -->
    <createDicItem ref="createDicItem" :addModal="addModal" :dicTypes="dicTypes" @refreshTable="getData"></createDicItem>
    <Card>
      <p slot="title">
        <Icon type="md-card"></Icon>
        {{ $t('dictionary-item-list') }}
      </p>
      <Button @click="openCreateForm" type="primary" slot="extra">{{ $t('new') }}</Button>
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
  import { dicItems } from '@/api/dictionary_item'
  import { allDicTypes } from '@/api/dictionary_type'
  import createDicItem from './components/create-dic-item.vue'
  export default {
    components: {
        createDicItem,
//            editDicType
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
            title: this.$t('dictionary_type'),
            key: 'dic_type',
          },
          {
            title: this.$t('item_name'),
            key: 'item_name',
          },
          {
            title: this.$t('item_value'),
            key: 'item_value',
          },
          {
            title: this.$t('enable'),
            key: 'enable',
          },
          {
            title: this.$t('sort'),
            key: 'sort',
          },
          {
            title: this.$t('comment'),
            key: 'comment',
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
//                                            this.openEditForm(params.row)//打开编辑页
                    }
                  }
                },this.$t('edit')),
                h('Poptip',{
                  props: {
                    confirm: true,
                    title: '确认要删除该配置项目吗?'
                  },
                  style: {
                    margin: '0 0 0 5px'
                  },
                  on: {
                    'on-ok': () => {

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
        dicTypes: [],

      }
    },
    methods: {
      getData () {
        if (this.loading) return

        this.loading = true

        dicItems(this.current, this.size).then(res => {
          this.data = res.data.data;
          this.total = res.data.meta.total;
          this.loading = false;
        })
      },
      getDicTypes () {
        allDicTypes(this.current, this.size).then(res => {
          let index = 0;
          for (let i in res.data.data) {
            this.dicTypes[index] = [];
            this.dicTypes[index]['id'] = i;
            this.dicTypes[index]['dic_name'] = res.data.data[i];
            index++;
          }

        })
      },
      handleChangeSize (val) {
        this.size = val;
        this.$nextTick(() => {
          this.getData();
        })
      },
      openCreateForm () {
        this.$refs.createDicItem.open();
      },
//            openEditForm (row) {
//                this.$refs.editRole.open(row);
//            }
    },
    mounted: function () {
      this.getData();
      this.getDicTypes();
    }
  }
</script>
