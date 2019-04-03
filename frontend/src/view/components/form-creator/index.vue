<template>
    <div>
        <Row>
            <Col span="10">
            <Card>
                <p>json editor</p>
                <json-editor :onChange="onChange" :json="jsonData" />
                <Button type="primary" @click="applyJson">Apply</Button>
                <Button type="primary" @click="saveJson">Save</Button>
            </Card>
            </Col>
            <Col span="14">
            <Card>
                <div id="form-create">
                    <form-create ref="fc" v-model="fApi" :rule="rule" :option="option"></form-create>
                </div>
            </Card>

            </Col>
        </Row>
    </div>

</template>
<script>
    import {maker} from 'form-create'
    import JsonEditor from '_c/json-editor'
    import { getFormData, addFormData } from '@/api/form'
    export default {
        name: 'myFormCreator',
        components: {
            JsonEditor
        },
        data () {
            return {
                jsonData: [],
                tempData: [],
                fApi:{},
                model: {},
                //表单生成规则
                rule: [],
                //组件参数配置
                option:{
                    //表单提交事件
                    onSubmit:function (formData) {
                        alert(JSON.stringify(formData));
                    }
                }
            };
        },
        mounted:function(){
            this.model = this.fApi.model();
            getFormData(1).then(res => {
                console.log(JSON.parse(res.data.data[0]))
                this.jsonData = JSON.parse(res.data.data[0])
                this.tempData = this.jsonData;
            })
//            this.jsonData = mock();
//            this.tempData = this.jsonData;
        },
        methods: {
            onChange(newJson) {
                // handle json changes
                console.log(newJson)
                this.tempData = newJson;
            },
            applyJson() {
                this.rule = null;
                this.rule = this.tempData;

                this.fApi.refresh()

            },
            saveJson() {
                addFormData(1,{ data: JSON.stringify(this.tempData)}).then(res => {
                    if(parseInt(res.data.code)) {
                        this.$Message.success('新增用户成功');
                    } else {
                        this.$Message.error(res.data.message);
                    }
                }).catch(function (error) {
                    this.$Message.error(error.response.data.message);
                });
            }
        }
    }
    function mock() {
        return [{
            type: 'hidden',
            field: 'id',
            value: '14'
        },{
            type: 'input',
            title: '商品名称',
            field: 'goods_name',
            value: '',
            props: {
                'type': 'text',
                'clearable': false,

                'disabled': false,

                'readonly': false,

                'rows': 4,

                'autosize': false,

                'number': false,

                'autofocus': false,

                'autocomplete': 'off',

                'placeholder': '请输入商品名称',

                'size': 'default',

                'spellcheck': false,

                'required': false,
            },
            validate: [{
                required: true,
                message: '请输入商品名称',
                trigger: 'blur'
            }],
            event:{
                change:function() {
                    console.log('ddd')
                }
            }
        }];
    }
</script>