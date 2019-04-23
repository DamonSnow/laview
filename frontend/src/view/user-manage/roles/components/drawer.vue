<template>
  <Drawer
    title="权限分配"
    :width="260"
    :mask="false"
    :closable="true"
    :draggable="true"
    placement="left"
    :value="value"
    @input="$emit('input', $event)"
  >
    <div slot="header"><span>角色[{{ role.name }}]的权限分配</span></div>
    <div style="height:inherit; overflow:auto; margin-bottom:10px">
      <Tree ref="tree" :data="data" show-checkbox></Tree>
    </div>
    <Button type="primary" @click="updRolePermission" long>提交</Button>
  </Drawer>
</template>
<script>
import { permissionByRoleId,updRolePermission } from "@/api/roles";
import { MyTreeTools } from "@/libs/util";

export default {
  name: "role-permissions-drawer",
  props: {
    value: {
      type: Boolean,
      default: false
    },
    role: {
      type: Object,
      default: {
          id: 0,
          name: ''
      }
    }
  },
  data() {
    return {
      data: []
    };
  },
  methods: {
    getPermissionByRoleId(roleId) {
      if (roleId) {
        permissionByRoleId( roleId ).then(res => {
          this.data = res.data.data
          console.log(this.data)
        });
      }
    },
    updRolePermission() {
      //获取权限ids
      let permissions = this.$refs.tree.getCheckedAndIndeterminateNodes();
      let permissionIds = [];
      for (let i = 0; i < permissions.length; i++) {
          permissionIds.push(permissions[i].id);
      }
      let _this = this;
      updRolePermission(_this.role.id, permissionIds).then(res => {
          if(parseInt(res.data.code) === 200) {
              this.$Message.success('更新角色['+ _this.role.name +']的权限成功');
              _this.$emit('refreshTable',false)
          } else {
              this.$Message.error(res.data.message);
          }
      }).catch(function (error) {
          _this.$Message.error(error.response.data.message);
      })
    }
  },
  watch: {
    role: function(val) {
      this.getPermissionByRoleId(val.id);
    }
  },
  mounted() {
      console.log(this.role)
    this.getPermissionByRoleId(this.role.id);
  }
};
</script>