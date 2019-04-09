<template>
  <div>
    <!-- 新增表单modal -->
    <createForm ref="createForm" :addModal="addModal" @refreshTable="getData"></createForm>
    <editForm ref="editForm" :addModal="addModal" @refreshTable="getData"></editForm>
    <formModel ref="formModel" :addModal="addModal"></formModel>
    <formPreview ref="formPreview" :addModal="addModal"></formPreview>
    <Card>
      <p slot="title">
        <Icon type="md-card"></Icon>
        {{ $t('Form List') }}
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
  import { formLists, toggleForm } from '@/api/form'

  import createForm from './components/create-form.vue'
  import editForm from './components/edit-form.vue'
  import formModel from './components/form-model.vue'
  import formPreview from './components/form-preview.vue'
  export default {
    components: {
      createForm,
      editForm,
      formModel,
      formPreview
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
            title: this.$t('Form Code'),
            key: 'form_code',
          },
          {
            title: this.$t('Form Name'),
            key: 'form_name',
          },
          {
            title: this.$t('Model'),
            key: 'form_model',
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
                                toggleForm(params.row.id, value).then(res => {
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
            title: this.$t('version'),
            key: 'version',
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
                    size: 'small',
                    disabled: !parseInt(params.row.version)
                  },
                  style: {
                    marginRight: '5px'
                  },
                  on: {
                    click: () => {
                      this.openPreview(params.row)//打开预览页
                    }
                  }
                },'预览'),
                h('Button', {
                    props: {
                      type : 'info',
                      size: 'small'
                    },
                    style: {
                      marginRight: '5px'
                    },
                    on: {
                      click: () => {
                        this.openEditInfo(params.row)//打开编辑信息页
                      }
                    }
                },this.$t('edit')),
                h('Button', {
                  props: {
                    type : 'info',
                    size: 'small'
                  },
                  on: {
                    click: () => {
                      this.openEditForm(params.row)//打开表单编辑页
                    }
                  }
                },'表单')
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

      }
    },
    methods: {
      getData () {
        if (this.loading) return

        this.loading = true

        formLists(this.current, this.size).then(res => {
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
        this.$refs.createForm.open();
      },
      openEditInfo (row) {
        this.$refs.editForm.open(row);
      },
      openEditForm (row) {
        this.$refs.formModel.open(row);
      },
      openPreview (row) {
        this.$refs.formPreview.open(row);
      }
    },
    mounted: function () {
      this.getData();
    }
  }
</script>
