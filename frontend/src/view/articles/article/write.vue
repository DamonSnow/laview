<template>
  <div>
  <Form ref="articleForm" :model="article" :label-width="80">
    <Row :gutter="16">
      <Col span="18">
        <Card>
            <h1 slot="title">
                <Icon type="ios-brush" />
                撰写文章
            </h1>
            <FormItem label="title" prop="title">
                <Input type="text" v-model="article.title"  placeholder="输入文章标题"></Input>
            </FormItem>
            <FormItem>
                <mavon-editor :toolbars="markdownOption" v-model="handbook"></mavon-editor>
            </FormItem>
        </Card>
      </Col>
      <Col span="6">
        <Row>
            <Col span="24">
            <Collapse v-model="publish">
                <Panel name="1">
                    发布
                    <div slot="content">
                        史蒂夫·乔布斯（Steve Jobs），1955年2月24日生于美国加利福尼亚州旧金山，美国发明家、企业家、美国苹果公司联合创办人。
                    </div>
                </Panel>

            </Collapse>
            </Col>
        </Row>
        <Row style="margin-top: 16px">
            <Col span="24">
            <Collapse v-model="categoryShow">
                <Panel name="1">
                    分类
                    <div slot="content">
                        史蒂夫·乔布斯（Steve Jobs），1955年2月24日生于美国加利福尼亚州旧金山，美国发明家、企业家、美国苹果公司联合创办人。
                    </div>
                </Panel>

            </Collapse>
            </Col>
        </Row>
        <Row style="margin-top: 16px">
            <Col span="24">
            <Collapse v-model="tagShow">
                <Panel name="1">
                    标签
                    <div slot="content">
                        <Input v-model="tags"></Input>
                    </div>
                </Panel>

            </Collapse>
            </Col>
        </Row>
      </Col>
    </Row>
  </Form>
  </div>

</template>
<script>
    import { mavonEditor } from 'mavon-editor'
    import 'mavon-editor/dist/css/index.css'
    export default {
        name: 'write-article',
        components: {
            mavonEditor
        },
        data () {
            return {
                markdownOption: {
                    bold: true, // 粗体
                    italic: true, // 斜体
                    header: true, // 标题
                    underline: true, // 下划线
                    strikethrough: true, // 中划线
                    mark: true, // 标记
                    superscript: true, // 上角标
                    subscript: true, // 下角标
                    quote: true, // 引用
                    ol: true, // 有序列表
                    ul: true, // 无序列表
                    link: true, // 链接
                    imagelink: true, // 图片链接
                    code: true, // code
                    table: true, // 表格
                    fullscreen: true, // 全屏编辑
                    readmodel: true, // 沉浸式阅读
                    htmlcode: true, // 展示html源码
                    help: true, // 帮助
                    undo: true, // 上一步
                    redo: true, // 下一步
                    trash: true, // 清空
                    save: true, // 保存（触发events中的save事件）
                    navigation: true, // 导航目录
                    alignleft: true, // 左对齐
                    aligncenter: true, // 居中
                    alignright: true, // 右对齐
                    subfield: true, // 单双栏模式
                    preview: true, // 预览
                },
                handbook: "#### how to use mavonEditor in nuxt.js",
                publish: '1',
                categoryShow: '1',
                tagShow: '1',
                article: {
                    title: '',
                    descriptions: '',
                    content: '',
                    cover_image: '',
                    category_id: 0,
                    enable: 1,
                    access_type: 1,
                    access_value: '',
                },
                tags: '',
                categories: ''
            };
        },
        mounted: function () {

        },
        methods: {

        }
    }

</script>