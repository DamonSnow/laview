<template>
  <div>
    <Modal
        ref="editUserModal"
        v-model="showModal"
        title="编辑用户信息"
        :loading="loading"
        @on-ok="handleSubmit('editUserForm')"
        @on-cancel="handleReset('editUserForm')">
      <p slot="header">{{ $t('edit-user') }}</p>
      <Alert type="error" v-if="errorMsg">{{ errorMsg }}</Alert>
      <Form ref="editUserForm" :model="user" :rules="ruleValidate" :label-width="80">
        <FormItem :label="$t('name')" prop="name">
          <Input v-model="user.name" :placeholder='$t("Enter User name")'></Input>
        </FormItem>
        <FormItem :label="$t('avatar')">
          <Row>
            <Col span="6">
            <Upload
                    :show-upload-list="false"
                    :on-success="uploadSuccess"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    :headers="uploadConfig.headers"
                    :max-size="uploadConfig.max_size"
                    :format="uploadConfig.format"
                    name="avatar"
                    type="drag"
                    :action="uploadConfig.uploadUrl"
                    style="display: inline-block;width:58px;">
              <div style="width: 58px;height:58px;">
                <Icon type="ios-camera" size="20"></Icon>
              </div>
            </Upload>
            </Col>
            <Col span="6">
            <div style="height:58px;padding: 10px">
              <Avatar
                      icon="ios-person"
                      :src="user.avatar"
                      size="large"
              />
            </div>
            </Col>
          </Row>

        </FormItem>
        <FormItem :label="$t('phone')">
          <Input v-model="user.phone"></Input>
        </FormItem>
        <FormItem :label="$t('active')">
          <i-switch v-model="user.active" size="large" :true-value="1" :false-value="0">
            <span slot="open">On</span>
            <span slot="close">Off</span>
          </i-switch>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>
<script>
  import { updateUser } from '@/api/user'
  import config from '@/config'
  import Cookies from 'js-cookie'
  import { TOKEN_KEY } from '@/libs/util'
  export default {
    name: 'edit-user-modal',
    props : {
      addUserModal : Boolean,
    },
    data () {
      return {
        errorMsg: '',
        showModal: this.addUserModal,
        loading: false,
        uploadConfig: {
          headers : {
              'x-access-token': Cookies.get(TOKEN_KEY),
              'Authorization': 'Bearer ' + Cookies.get(TOKEN_KEY),
          },
          format: ['jpg', 'jpeg', 'png'],
          max_size: 500,
          uploadUrl: config.baseUrl.pro + '/uploadAvatar',
        },
        userId: 0,
        user: {
          name: '',
          avatar: '',
          phone: '',
          active: 0
        },
        ruleValidate: {
          name: [
            { required: true, message: '姓名不能为空', trigger: 'blur' }
          ],
        }
      }
    },

    methods: {
      handleSubmit (name) {
        let _this=this;
        _this.errorMsg = '';
        this.$refs[name].validate((valid) => {
          if (valid) {
            updateUser(_this.userId, _this.user).then(res => {
              if(parseInt(res.data.code) === 200) {
                this.$Message.success('更新用户信息成功');
                _this.$emit('refreshTable',false)
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                _this.showModal = true;
              }
            }).catch(function (error) {
              _this.$refs.editUserModal.visible = true;
              _this.showModal =true;
              let message = '';
              for (let i in error.response.data.message) {
                message = message + error.response.data.message[i];
              }
              _this.errorMsg = message;
            })
          } else {
            _this.$refs.editUserModal.visible = true;
            _this.showModal = true;
          }
        })

      },
      handleReset (name) {
        this.errorMsg = '';
        this.$refs[name].resetFields();
        this.user.phone = '';
      },
      open (row) {
        this.userId = row.user_id;
        this.user.name = row.name;
        this.user.avatar = row.avatar;
        this.user.phone = row.phone;
        this.user.active = row.active;
        this.showModal = true;
      },
      uploadSuccess (res, file) {
        file.name = res.data.file_name;
        file.url = res.data.path;
        this.user.avatar = res.data.path;
      },
      handleFormatError(file) {
          this.$Notice.warning({
              title: '文件格式不正确',
              desc: '文件 ' + file.name + ' 格式不正确，请上传 jpg 或 png 格式的图片。'
          });
      },
      handleMaxSize(file) {
          this.$Notice.warning({
              title: '超出文件大小限制',
              desc: '文件 ' + file.name + ' 太大，不能超过 ' + this.uploadConfig.max_size + 'kb'
          });
      },
    },
  }
</script>