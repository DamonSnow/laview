<style lang="less">
    @import '@/assets/styles/common.less';
    @import '../../tables/components/table.less';
</style>

<template>
    <div>
        <Row>
            <Card>
                <p slot="title">
                    <Icon type="person"></Icon>
                    角色列表
                </p>
                <Row>
                    <Row>
                        <Input v-model="jobNum" placeholder="工号" style="width: 200px" />
                        <span @click="handleSearch" style="margin: 0 10px;"><Button type="primary" icon="search">搜索</Button></span>
                        <Button @click="handleCancel" type="ghost" >取消</Button>
                        <AddUserModal style="float:right;"></AddUserModal>
                    </Row>
                    <Col>
                    <Row class="margin-top-10">
                        <Table :loading="loading" :data="tableData1" :columns="tableColumns1" stripe ref="table2image"></Table>
                    </Row>
                    <br>
                    <Row>
                        <Page show-total placement="top" :total="total" :current="current" @on-page-size-change="pageChange" @on-change="change" size="small" show-elevator show-sizer></Page>
                    </Row>
                    </Col>

                </Row>
            </Card>
        </Row>
    </div>
</template>

<script>
import html2canvas from 'html2canvas';
import Util from '@/libs/util';
import AddUserModal from './create.vue'
export default {
    name: 'table-to-image',
    components : {
        AddUserModal
    },
    data () {
        return {
            loading : true,
            total : 10,
            current : 1,
            pageSize : 10,
            tableData1: [],
            imageName: '',
            jobNum: '',
            tableColumns1: [
                {
                    title: '角色名称',
                    key: 'name'
                },
                {
                    title: '标识',
                    key: 'name_zh',
                },
                {
                    title: '权限',
                    key: 'auths',
                    render: (h, params) => {
                        const text = params.row.role;
                        if(!Object.is(text, null) && text.length > 0) {
                            console.log();
                            return text.map(tag => {
                                return h('tag', {
                                    props: {
                                        color: 'blue'
                                    }
                                }, tag);
                            })

                        }

                    }
                },
                {
                    title: '备注',
                    key: 'comment'
                },
                {
                    title: '建立时间',
                    key: 'create_at',
                },
                {
                    title: '更新时间',
                    key: 'update_at',
                },

            ],

        };
    },
    mounted : function () {
        let _this = this;
        _this.loading = true;
        Util.ajax.get('/test').then(function (response) {
                console.log(response.data);
                _this.tableData1 = [];
                response.data.data.forEach(function (v,k) {
                    _this.tableData1.push({
                        jobNum: v.jobNum,
                        account: v.jobNum,
                        name: v.name,
                        role: v.roles,
                        mail: v.email,
                        depart: v.dept,
                    });
                });
                _this.total = response.data.meta.total;
                _this.current = response.data.meta.current_page;

        }).then(function () {
            _this.loading = false;
        });

    },

    methods: {
        toggleShow () {
            this.addUserModal = !this.addUserModal;
            console.log(this.addUserModal);
        },
        pageChange (pageSize) {

            let _this = this;
            _this.pageSize = pageSize;
            _this.loading = true;
            Util.ajax.get('/test'+'?page='+_this.current+'&per='+pageSize).then(function (response) {
                console.log(response.data);
                _this.tableData1 = [];
                response.data.data.forEach(function (v,k) {

                    _this.tableData1.push({
                        jobNum: v.jobNum,
                        account: v.jobNum,
                        name: v.name,
                        role: v.roles,
                        mail: v.email,
                        depart: v.dept,
                    });
                });
                _this.total = response.data.meta.total;
                _this.current = response.data.meta.current_page;

            }).then(function () {
                _this.loading = false;
            });
        },
        change (index) {
            let _this = this;
            let para = {
                jobNum : _this.jobNum
            };
            _this.loading = true;
            Util.ajax.post('/test'+'?page='+index+'&per='+_this.pageSize,para).then(function (response) {
                console.log(response.data);
                _this.tableData1 = [];
                response.data.data.forEach(function (v,k) {

                    _this.tableData1.push({
                        jobNum: v.jobNum,
                        account: v.jobNum,
                        name: v.name,
                        role: v.roles,
                        mail: v.email,
                        depart: v.dept,
                    });
                });
                _this.total = response.data.meta.total;
                _this.current = response.data.meta.current_page;
            }).then(function () {
                _this.loading = false;
            });
        },
        handleSearch () {
            this.change(1);
        },
        handleCancel () {
            this.jobNum = '';
            this.change(1);
        },

    }
};
</script>
