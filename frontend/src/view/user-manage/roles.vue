<template>
  <div>
        <!-- 封装成组件 -->
    <Modal
      v-model="addModal"
      title="Title"
      :loading="loading"
      width="700"
      @on-ok="handleSubmit('roleForm')"
      @on-cancel="handleReset('roleForm')">
      <p slot="header">{{ $t('add-role') }}</p>
        <Form ref="roleForm" :model="role" :rules="ruleValidate" :label-width="80">
            <FormItem label="角色" prop="name">
                <Input v-model="role.name" placeholder="Enter Role Name"></Input>
            </FormItem>
            <FormItem label="权限" prop="permissions">
                <Transfer
                    :data="rights"
                    :target-keys="role.permissions"
                    :render-format="permission_rendor"
                    @on-change="handlePermission"></Transfer>
            </FormItem>
            <FormItem label="备注">
                <Input v-model="role.comment" placeholder="备注"></Input>
            </FormItem>
        </Form>
    </Modal>

        <!-- 封装成组件 -->
    <Card>
      <p slot="title">
        <Icon type="ios-film-outline"></Icon>
        {{ $t('permissions-list') }}
      </p>
      <Button @click="addModal = true" type="primary" slot="extra">{{ $t('add-role') }}</Button>
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
  import { roles, addRole } from '@/api/roles'
  import { allPermissions } from '@/api/permissions'
  export default {
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
            title: '角色',
            key: 'name',
          },
          {
            title: '备注',
            key: 'comment'
          },
          {
            title: '操作',
            key: 'id',
          }
        ],
        data: [],
        loading: false,
        total: 0,
        current: 1,
        size: 10,
        addModal: false,
        rights: [],
        role: {
            name: '',
            permissions: [],
            comment: ''
        },
        ruleValidate: {
          name: [
            { required: true, message: 'The name cannot be empty', trigger: 'blur' }
          ],
          permissions: [
            { required: true, type: 'array', min: 1, message: 'Choose at least one hobby', trigger: 'change' },
          ],
        },

      }
    },
    methods: {
      getData () {
        if (this.loading) return

        this.loading = true

        roles(this.current, this.size).then(res => {

          this.data = res.data.data;
          this.total = res.data.meta.total;
          this.loading = false;
        })
      },
      getPermissions () {
          allPermissions(this.current, this.size).then(res => {
              this.rights = res.data.data.reduce(function (per, item, index) {
                  console.log(per);
                  per[index] = [];
                  per[index]['key'] = item['id'];
                  per[index]['label'] = item['name'];
                  per[index]['disable'] = false;
                  return per;
              },[]);
          })
      },
      handleChangeSize (val) {
        this.size = val;
        this.$nextTick(() => {
          this.getData();
        })
      },
      handleSubmit (name) {
          let _this = this;
          _this.addModal = false;
            this.$refs[name].validate((valid) => {console.log(valid);
                if (valid) {
                    console.log(_this.role);
                    addRole(_this.role.name, _this.role.permissions, _this.role.comment).then(res => {
                        console.log(res.data)
                        if(parseInt(res.data.code) === 200) {
                            this.$Message.success('新增权限成功');
                            this.getData();
                            _this.addModal = false;
                        } else {
                            this.$Message.error(res.data.message);
                            this.addModal =true;
                        }
                    }).catch(function (error) {
                        _this.addModal =true;
                        _this.$Message.error(error.response.data.message);
                        _this.handleReset('permissionForm')
                    })

                } else {
                    _this.addModal = true;
                }
            })
      },
      handleReset (name) {
            this.$refs[name].resetFields();
      },
      handlePermission (newTargetKeys, direction, moveKeys) {
          console.log(newTargetKeys);
          console.log(direction);
          console.log(moveKeys);
          this.role.permissions = newTargetKeys;
      },
      permission_rendor (item) {
        return item.label;
      },
    },
    mounted: function () {
      this.getData();
      this.getPermissions();
    }
  }
</script>
