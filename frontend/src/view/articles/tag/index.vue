<template>
  <div>
    <!-- 新增标签modal -->
    <addTag ref="addTag" :addModal="addModal" :rights="rights" @refreshTable="getData"></addTag>
    <!-- 编辑标签modal -->
    <editTag ref="editTag" :addModal="addModal" @refreshTable="getData"></editTag>
    <Card>
      <p slot="title">
        <Icon type="md-card"></Icon>
        {{ $t('tags') }}
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
  import { tags } from '@/api/tags'
  import addTag from './components/add-tags.vue'
  import editTag from './components/edit-tags.vue'
  export default {
    components: {
      addTag,
      editTag
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
            title: this.$t('tag_name'),
            key: 'name',
          },
          {
            title: this.$t('tag_slug'),
            key: 'slug',
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
        tags(this.current, this.size).then(res => {
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
        this.$refs.addTag.open();
      },
      openEditForm (row) {
        this.$refs.editTag.open(row);
      }
    },
    mounted: function () {
      this.getData();
    }
  }
</script>
