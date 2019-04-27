<template>
  <div>
    <!-- 新增标签modal -->
    <!--<addTag ref="addTag" :addModal="addModal" :rights="rights" @refreshTable="getData"></addTag>-->
    <!-- 编辑标签modal -->
    <!--<editTag ref="editTag" :addModal="addModal" @refreshTable="getData"></editTag>-->
    <Card>
      <p slot="title">
        <Icon type="md-card"></Icon>
        {{ $t('articles') }}
      </p>
      <Button to="/articles/write" type="primary" slot="extra">{{ $t('write_article') }}</Button>
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
  import { articles } from '@/api/articles'
//  import addTag from './components/add-tags.vue'
//  import editTag from './components/edit-tags.vue'
  export default {
    components: {
//      addTag,
//      editTag
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
            title: this.$t('title'),
            key: 'title',
          },
          {
            title: this.$t('user'),
            key: 'user',
          },
          {
            title: this.$t('categories'),
            key: 'categories',
            render: (h, params) => {
              if(params.row.categories.length > 0) {
                let cols = params.row.categories.map(item => {
                  return h('Tag', {
                    props: {
                      color: 'primary'
                    }
                  }, item);
                })
                return h('div', cols)
              }

            }
          },
          {
            title: this.$t('tags'),
            key: 'tags',
            render: (h, params) => {
              if(params.row.tags.length > 0) {
                let cols = params.row.tags.map(item => {
                  return h('Tag', {
                    props: {
                      color: 'primary'
                    }
                  }, item);
                })
                return h('div', cols)
              }
            }
          },
          {
            title: this.$t('view_count'),
            key: 'view_count',
            render: (h, params) => {
              return h('Tag', {
                props: {
                  color: '#c5c8ce'
                }
              },params.row.view_count)
            }
          },
          {
            title: this.$t('comment_count'),
            key: 'comment_count',
            render: (h, params) => {
              return h('Tag', {
                props: {
                  color: '#c5c8ce'
                }
              },params.row.comment_count)
            }
          },
          {
            title: this.$t('vote/collection'),
            key: 'collection_count',
            render: (h, params) => {
                return h('div', {

                },params.row.vote_count + '/' + params.row.collection_count)
            }
          },
          {
            title: this.$t('status'),
            key: 'enable',
            render: (h, params) => {
              let color = '';
              let text = '';
              switch (parseInt(params.row.enable)) {
                case 0:
                  color = '#ed4014';
                  text = '禁用';
                  break;
                case 1:
                  color = '#c5c8ce';
                  text = '草稿';
                  break;
                case 2:
                  color = '#19be6b';
                  text = '启用';
                  break;
                default:
                  color = '#c5c8ce';
                  text = '草稿';
              }
              return h('Tag', {
                props: {
                  color: color
                }
              },text)
            }
          },
          {
              title: this.$t('publish_at'),
              key: 'publish_at',
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
                      this.$router.push(`/articles/write/${params.row.id}`)
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
        articles(this.current, this.size).then(res => {
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
//      openEditForm (row) {
//        this.$refs.editTag.open(row);
//      }
    },
    mounted: function () {
      this.getData();
    }
  }
</script>
