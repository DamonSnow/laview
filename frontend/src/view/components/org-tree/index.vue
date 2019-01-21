<template>

  <Card shadow style="height: 100%;width: 100%;overflow:hidden">
    <!-- 新增branch的model -->
    <createModel ref="createBranch" :addModal="addModal" @refreshTable="getDepartmentData"></createModel>
    <!-- 编辑branch的model -->
    <editModel ref="editBranch" :addModal="addModal" @refreshTable="getDepartmentData"></editModel>
    <div class="department-outer">
      <div class="tip-box">
        <Button v-if="!data" @click="rootForm" type="primary" slot="extra">添加根节点</Button>
      </div>
      <div class="zoom-box">
        <zoom-controller v-model="zoom" :min="20" :max="200"></zoom-controller>
      </div>
      <div class="view-box">
        <org-view
          v-if="data"
          :data="data"
          :zoom-handled="zoomHandled"
          @on-menu-click="handleMenuClick"
        ></org-view>
      </div>
    </div>
  </Card>
</template>

<script>
  import OrgView from './components/org-view.vue'
  import ZoomController from './components/zoom-controller.vue'
  import {getOrgData} from '@/api/data'
  import createModel from './components/create-org.vue'
  import editModel from './components/edit-org.vue'
  import {delBranch} from '@/api/branches'
  import './index.less'
  const menuDic = {
    edit: '编辑部门',
    detail: '查看部门',
    new: '新增子部门',
    delete: '删除部门'
  }
  export default {
    name: 'org_tree_page',
    components: {
      OrgView,
      ZoomController,
      createModel,
      editModel
    },
    data () {
      return {
        data: null,
        zoom: 100,
        addModal: false
      }
    },
    computed: {
      zoomHandled () {
        return this.zoom / 100
      }
    },
    methods: {
      setDepartmentData (data) {
        data.isRoot = true
        return data
      },
      handleMenuClick ({data, key}) {
        switch (key) {
          case 'edit':
            this.$refs.editBranch.open(data);
            break;
          case 'detail':
            this.$Modal.info({
              title: '详情',
              content: '<p>部门名称：' + data.label + '</p><p>部门编号：' + data.code + '</p>'
            });
            break;
          case 'new':
            this.$refs.createBranch.open(data.id);
            break;
          case 'delete':
            this.$Modal.confirm({
              title: '删除部门及其子部门',
              content: '<p>确定要删除该部门及其子部门？</p>',
              loading: true,
              onOk: () => {
                delBranch(data.id).then(res => {

                  if (parseInt(res.data.code) === 200) {
                    this.getDepartmentData()
                    this.$Modal.remove();
                    this.$Message.success('删除部门成功');
                  } else {
                    this.$Message.error(res.data.message);
                    this.$Modal.remove();
                  }
                }).catch(function (error) {
                  this.$Modal.remove();
                  _this.$Message.error(error.response.data.message);
                })
              }
            });
            break;
        }

      },
      getDepartmentData () {
        getOrgData().then(res => {
          const {data} = res.data
          this.data = data
        })
      },
      rootForm () {
        this.$refs.createBranch.open(0);
      }
    },
    mounted () {
      this.getDepartmentData()
    }
  }
</script>

<style>
</style>
