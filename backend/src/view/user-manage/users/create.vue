<template>
    <div>
    <Button type="primary" @click="addUserModal=true">新增用户</Button>
    <Modal v-model="addUserModal">
        <p slot="header" style="text-align:center">
            <Icon type="person-add"></Icon>
            <span>新增用户</span>
        </p>
        <div style="text-align:center">
            <Form :model="formItem" :label-width="80">
                <FormItem label="工号">
                    <Input v-model="formItem.jobNum" placeholder="工号"></Input>
                </FormItem>
                <FormItem label="姓名">
                    <Input v-model="formItem.name" placeholder="姓名"></Input>
                </FormItem>
                <FormItem label="部门">
                    <Select v-model="formItem.dept">
                        <Option value="1">制造处</Option>
                        <Option value="2">工程处</Option>
                        <Option value="3">研发处</Option>
                    </Select>
                </FormItem>
                <FormItem label="Email">
                    <Input v-model="formItem.email" placeholder="Email"></Input>
                </FormItem>
                <FormItem label="联系方式">
                    <Input v-model="formItem.phone" placeholder="联系方式"></Input>
                </FormItem>
                <FormItem label="角色">
                    <Select v-model="formItem.role" multiple>
                        <Option v-for="role in roles" :value="role.value" :key="role.value">{{ role.label }}</Option>
                    </Select>
                </FormItem>
            </Form>
        </div>
        <div slot="footer">
            <Button @click="close">取消</Button>
            <Button type="info" :loading="modal_loading" @click="addUser">新增</Button>
        </div>
    </Modal>
    </div>
</template>
<script>
    export default {
        name: 'add-user-modal',
        data () {
            return {
                addUserModal : false,
                modal_loading: false,
                formItem: {
                    jobNum: '',
                    name: '',
                    dept: '',
                    email: '',
                    phone: '',
                    role: []
                },
                roles: [
                    {
                        value: 'New York',
                        label: 'New York'
                    },
                    {
                        value: 'London',
                        label: 'London'
                    },
                    {
                        value: 'Sydney',
                        label: 'Sydney'
                    },
                    {
                        value: 'Ottawa',
                        label: 'Ottawa'
                    },
                    {
                        value: 'Paris',
                        label: 'Paris'
                    },
                    {
                        value: 'Canberra',
                        label: 'Canberra'
                    }
                ],
            }
        },
        methods: {
            addUser () {
                let _this=this;
                _this.modal_loading = true;
                console.log(_this.formItem);
                setTimeout(() => {
                    _this.modal_loading = false;
                    _this.addUserModal = !_this.addUserModal;
                    _this.$Message.success('Successfully delete');
                }, 2000);
            },
            close () {
                this.addUserModal = !this.addUserModal;
                console.log(this.currentValue);

            }
        }
    }
</script>
