<template>
    <div>
        <Row>
            <Col span="10">
            <Card>
                <p slot="title">
                    <Icon type="md-aperture"/>
                    Json规则
                    <small>详见<a target="_blank" href="http://www.form-create.com/">form-create</a></small>
                </p>
                <!--<json-editor :onChange="onChange" :json="jsonData" />-->
                <Row>
                    <Col span="24">
                    <Input type="textarea" v-model="jsonData" :rows="10" @click="applyJson"></Input>
                    </Col>
                </Row>
                <Row style="margin-top: 10px;">
                    <Col span="8" offset="16">
                    <Button type="primary" @click="applyJson">Apply</Button>
                    <Button type="primary" style="margin-left: 10px;" @click="saveJson">Save</Button>
                    </Col>
                </Row>

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
    import {getFormData} from '@/api/form'
    export default {
        name: 'myFormCreator',
        components: {
            JsonEditor
        },
        data () {
            return {
                jsonData: '',
                tempData: [],
                fApi: {},
                model: {},
                //表单生成规则
                rule: [],
                //组件参数配置
                option: {
                    //表单提交事件
                    onSubmit: function (formData) {
                        alert(JSON.stringify(formData));
                    }
                }
            };
        },
        mounted: function () {
            this.model = this.fApi.model();
            getFormData(1).then(res => {
//                console.log(JSON.parse(res.data.data[0]))
                this.jsonData = res.data.data[0]
                this.tempData = this.jsonData;
            })
//            this.jsonData = obj2string(mock());console.log(string2obj(this.jsonData))
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
                this.rule = string2obj(this.tempData);

                this.fApi.refresh()

            },
            saveJson() {
//                addFormData(1, {data: this.tempData}).then(res => {
//                    if (parseInt(res.data.code)) {
//                        this.$Message.success('新增用户成功');
//                    } else {
//                        this.$Message.error(res.data.message);
//                    }
//                }).catch(function (error) {
//                    this.$Message.error(error.response.data.message);
//                });
            }
        }
    }
    function mock() {
        return [{
            type: 'hidden',
            field: 'id',
            value: '14'
        }, {
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
            event: {
                change: function () {
                    console.log('ddd')
                }
            }
        }];
    }
    function obj2string(o) {
        var r = [];
        if (typeof o == "string") {
            return "\"" + o.replace(/([\'\"\\])/g, "\\$1").replace(/(\n)/g, "\\n").replace(/(\r)/g, "\\r").replace(/(\t)/g, "\\t") + "\"";
        }
        if (typeof o == "object") {
            if (!o.sort) {
                for (var i in o) {
                    r.push(i + ":" + obj2string(o[i]));
                }
                if (!!document.all && !/^\n?function\s*toString\(\)\s*\{\n?\s*\[native code\]\n?\s*\}\n?\s*$/.test(o.toString)) {
                    r.push("toString:" + o.toString.toString());
                }
                r = "{" + r.join() + "}";
            } else {
                for (var i = 0; i < o.length; i++) {
                    r.push(obj2string(o[i]))
                }
                r = "[" + r.join() + "]";
            }
            return r;
        }
        return o.toString();
    }
    function string2obj(str) {
        return new Function('return ' + str + ';')()
    }
</script>