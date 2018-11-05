<template>
  <div>
    <Modal
      ref="createUserModal"
      v-model="showModal"
      title="新增用户"
      :loading="loading"
      @on-ok="handleSubmit('userForm')"
      @on-cancel="handleReset('userForm')">
      <p slot="header">新增用户</p>
      <Alert type="error" v-if="errorMsg">{{ errorMsg }}</Alert>
      <Form ref="userForm" :model="user" :rules="ruleValidate" :label-width="80">
        <FormItem label="姓名" prop="name">
          <Input v-model="user.name" :placeholder='$t("Enter User name")'></Input>
        </FormItem>
        <FormItem label="头像">
          <div>
            <Avatar
                  icon="ios-person"
                  :src="user.avatar"
                  />
            <Upload
                    :show-upload-list="false"
                    :on-success="uploadSuccess"

                    :headers="uploadConfig.headers"

                    name="avatar"

                    :action="uploadConfig.uploadUrl"
                    style="display: inline-block;width:58px;"
            >
              <Button icon="ios-cloud-upload-outline">Upload files</Button>
            </Upload>
            <!--<Upload :action="uploadUrl"-->
                    <!--:headers="headers"-->
                    <!--:on-success="uploadSuccess"-->
                    <!--:before-upload="handleBeforeUpload">-->
              <!---->
            <!--</Upload>-->
          </div>
        </FormItem>
        <FormItem label="E-mail" prop="email">
          <Input v-model="user.email"></Input>
        </FormItem>
        <FormItem label="工号" prop="job_number">
          <Input v-model="user.job_number"></Input>
        </FormItem>
        <FormItem label="手机号">
          <Input v-model="user.phone"></Input>
        </FormItem>
    </Form>
    </Modal>
   </div>
</template>
<script>
    import { addUser } from '@/api/user'
    import config from '@/config'
    import Cookies from 'js-cookie'
    import { TOKEN_KEY } from '@/libs/util'
    export default {
        name: 'add-user-modal',
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

                user: {
                    name: '',
                    avatar: '',
                    email: '',
                    job_number: '',
                    phone: '',
                },
                ruleValidate: {
                    name: [
                        { required: true, message: '姓名不能为空', trigger: 'blur' }
                    ],
                    email: [
                        { required: true, message: 'email不能为空', trigger: 'blur' },
                        { type: 'email', message: 'email格式不正确', trigger: 'blur' }
                    ],
                    job_number: [
                        { required: true, message: '工号不能为空', trigger: 'blur' }
                    ],
                }
            }
        },

        methods: {
            handleSubmit (name) {
                let _this=this;
                _this.errorMsg = '';
                this.$refs[name].validate((valid) => {


                    this.$refs[name].validate((valid) => {
                        if (valid) {
                           addUser(_this.user).then(res => {
                               if(parseInt(res.data.code) === 200) {
                                   this.$Message.success('新增用户成功');
                                   _this.$emit('refreshTable',false)
                                   _this.showModal = false;
                               } else {
                                   this.$Message.error(res.data.message);
                                   _this.showModal = true;
                               }
                           }).catch(function (error) {
                               _this.$refs.createUserModal.visible = true;
                               _this.showModal =true;
                               let message = '';
                               for (let i in error.response.data.message) {
                                   message = message + error.response.data.message[i];
                               }
                               _this.errorMsg = message;
//                               _this.handleReset('userForm')
                           })
                        } else {
                            _this.$refs.createUserModal.visible = true;
                            _this.showModal = true;
                        }
                    })
                });

            },
            handleReset (name) {
                this.errorMsg = '';
                this.$refs[name].resetFields();
                this.user.phone = '';
//            _this.showAddModel = false;
//            _this.$emit('showModel',false)
            },
            open () {
                this.showModal = true;
            },
            uploadSuccess (res, file) {
                console.log(res)
                file.name = res.data.name;
                file.url = res.data.url;
                this.user.avatar = res.data.url;
            },
            handleBeforeUpload (file) {
                console.log(file)
            }
        },
    }
</script>