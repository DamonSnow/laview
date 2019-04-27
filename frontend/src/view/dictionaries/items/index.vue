<template>
  <div>
    <!-- 新增字典项目modal -->
    <createDicItem ref="createDicItem" :addModal="addModal" :dicTypes="dicTypes" @refreshTable="getData"></createDicItem>
    <editDicItem ref="editDicItem" :addModal="addModal" :dicTypes="dicTypes" @refreshTable="getData"></editDicItem>
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
          show-elevator
          @on-change="getData"
          @on-page-size-change="handleChangeSize"
        ></Page>
      </div>

    </Card>
  </div>
</template>

<script>
  import { dicItems, toggleDicItem } from '@/api/dictionary_item'
  import { allDicTypes } from '@/api/dictionary_type'
  import createDicItem from './components/create-dic-item.vue'
  import editDicItem from './components/edit-dic-item.vue'
  export default {
    components: {
      createDicItem,
      editDicItem
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
            render: (h, params) => {
                return h('div', [

                    h('i-switch', {
                        props: {
                            type: 'success',
                            value: params.row.enable,
                            'true-value': 1,
                            'false-value': 0
                        },
                        style: {
                            marginRight: '5px'
                        },
                        on: {
                            'on-change': (value) => {
                                //参数value是回调值，并没有使用到
                                toggleDicItem(params.row.id, value).then(res => {
                                    params.row.enable = value;
                                })
                            }
                        }
                    },[
                        h('span', {
                            slot: 'open',
                            domProps: {
                                innerHTML: '开'
                            }
                        }),
                        h('span', {
                            slot: 'close',
                            domProps: {
                                innerHTML: '关'
                            }
                        })
                    ])
                ]);
            }
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
                      this.openEditForm(params.row)//打开编辑页
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
      openEditForm (row) {
        this.$refs.editDicItem.open(row);
      }
    },
    mounted: function () {
      this.getData();
      this.getDicTypes();
    }
  }
</script>
