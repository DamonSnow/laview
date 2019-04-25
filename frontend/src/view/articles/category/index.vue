<template>
  <div>
    <!-- 新增分类目录的model -->
    <addCategory ref="createCategory" :addModal="addModal" @refreshTable="getData"></addCategory>
    <!-- 编辑分类目录的model -->
    <editCategory ref="editCategory" :addModal="addModal" @refreshTable="getData"></editCategory>

    <Card>
      <p slot="title">
        <Icon type="ios-lock"></Icon>
        {{ $t('categories') }}
      </p>
      <Button @click="openCreateForm(0)" type="primary" slot="extra">{{ $t('add') }}</Button>
      <!--<Table :columns="columns" stripe :data="data" :loading="loading" border size="small"></Table>-->
      <TreeTable
              :data="data"
              :columns="columns"
              @on-load-children="onloadChildren"
              @on-remove-children="onRemoveChildren"
              @on-checked-change="onCheckedChange"
              @on-toggle-expand="onToggleExpand"
      />
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
  import { categories, deleteCategory, getChildrenCategories } from '@/api/categories'
  import TreeTable from "_c/treetable";
  import addCategory from './components/add-category.vue'
  import editCategory from './components/edit-category.vue'
  export default {
    components: {
      addCategory,
      editCategory,
      TreeTable
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
            type: 'treeNode',
            title: this.$t('category_name'),
            key: 'name',
          },
          {
              title: this.$t('category_code'),
              key: 'code',
          },
          {
            title: this.$t('comment'),
            key: 'description'
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
                      this.openCreateForm(params.row.id)//打开编辑页
                    }
                  }
                },this.$t('add')),
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
//                h('Poptip',{
//                  props: {
//                    confirm: true,
//                    title: '确认要删除该权限吗?'
//                  },
//                  style: {
//                    margin: '0 0 0 5px'
//                  },
//                  on: {
//                    'on-ok': () => {
//                      let _this = this;
//                      deleteCategory(params.row.id).then(res => {
//                        this.$Message.success('删除分类成功');
//                        if(parseInt(params.row._index) === 0 && this.current !== 1) {
//                          this.current --;
//                        }
//                        this.getData();
//                      }).catch(function (error) {
//                          _this.$Message.error(error.response.data.message);
//                      })
//                    }
//                  }
//                },[
//                  h('Button',{
//                    props: {
//                        type : 'error',
//                        size: 'small'
//                    },
//                  },this.$t('delete'))
//                ],this.$t('delete'))
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
      }
    },
    methods: {
      getData () {
        if (this.loading) return

        this.loading = true

        categories(this.current, this.size).then(res => {

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
      openCreateForm (parent_id = 0) {
        this.$refs.createCategory.open(parent_id);
      },
      openEditForm (row) {
        this.$refs.editCategory.open(row);
      },
      onToggleExpand(row, index, isExpand) {
        this.data[index]._isExpand = isExpand;
      },
      onloadChildren(row, index, level, callback) {
        let vm = this;
        getChildrenCategories(row.id).then(function(res) {
            if (res.data.data) {
                console.log(res.data.data)
                vm.data = callback(index, level, res.data.data);
            }
        });
      },
      onRemoveChildren(startIndex, howmany) {
          this.data.splice(startIndex, howmany);
      },
      onCheckedChange(checking) {
        this.checking = checking;
      },
    },
    mounted: function () {
      this.getData()
    }
  }
</script>
