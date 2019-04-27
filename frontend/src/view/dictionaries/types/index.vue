<template>
  <div>
    <!-- 新增字典类型modal -->
    <createDicType ref="createDicType" :addModal="addModal" :rights="rights" @refreshTable="getData"></createDicType>
    <!-- 编辑字典类型modal -->
    <editDicType ref="editDicType" :addModal="addModal" @refreshTable="getData"></editDicType>
    <Card>
      <p slot="title">
        <Icon type="md-card"></Icon>
        {{ $t('dictionary-type-list') }}
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
  import { dicTypes } from '@/api/dictionary_type'
  import createDicType from './components/create-dic-type.vue'
  import editDicType from './components/edit-dic-type.vue'
  export default {
    components: {
      createDicType,
      editDicType
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
            title: this.$t('dictionary_code'),
            key: 'dic_code',
          },
          {
            title: this.$t('dictionary_name'),
            key: 'dic_name',
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
                },this.$t('edit')),
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
        dicTypes(this.current, this.size).then(res => {
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
      openCreateForm () {
        this.$refs.createDicType.open();
      },
      openEditForm (row) {
        this.$refs.editDicType.open(row);
      }
    },
    mounted: function () {
      this.getData();
    }
  }
</script>
