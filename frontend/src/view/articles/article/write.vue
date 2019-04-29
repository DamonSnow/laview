<template>
  <div>
  <Form ref="articleForm" :model="article" :label-width="80">
    <Row :gutter="16">
      <Col span="20">
        <Card>
            <h1 slot="title">
                <Icon type="ios-brush" />
                {{ $t('write_article') }}
            </h1>
            <FormItem :label="$t('title')" prop="title">
                <Input type="text" v-model="article.title"  placeholder="输入文章标题"></Input>
            </FormItem>
            <FormItem :label="$t('description')" prop="description">
                <Input type="text" v-model="article.descriptions"  placeholder="文章描述"></Input>
            </FormItem>
            <Row>
                <Col span="24">
                    <mavon-editor
                            :style="{height:setHeight+'px'}"
                            ref="mdeditor"
                            :toolbars="markdownOption"
                            v-model="article.content"
                            :ishljs = "true"
                            codeStyle='atom-one-dark'
                            @imgAdd="imgAdd"
                            @imgDel="imgDel"
                    ></mavon-editor>
                </Col>
            </Row>

            <!--:toolbarsFlag=false :subfield=false :defaultOpen="preview"-->
            <Row  style="margin-top: 16px">
                <Col span="12">
                <FormItem :label="$t('cover')">
                    <cropper
                            :src="imgSrc"
                            crop-button-text="确认提交"
                            @on-crop="handleCroped"
                    ></cropper>
                </FormItem>
                </Col>
                <Col span="12">
                <img :src="article.cover_image" style="width: 100%;height: 100%" alt="">
                </Col>
            </Row>


        </Card>
      </Col>
      <Col span="4">
        <Row>
            <Col span="24">
            <Collapse v-model="publishBlock">
                <Panel name="1">
                    {{ $t('publish') }}
                    <div slot="content">
                        <FormItem :label="$t('status')">
                            <Select v-model="article.enable">
                                <Option  :value="1">草稿</Option>
                                <Option  :value="0">禁用</Option>
                                <Option  :value="2">启用</Option>
                            </Select>
                        </FormItem>
                        <FormItem :label="$t('public')">
                            <Select v-model="article.access_type">
                                <Option  :value="1">公开</Option>
                                <Option  :value="2">私密</Option>
                                <Option  :value="3">密码访问</Option>
                            </Select>
                        </FormItem>
                        <FormItem v-if="article.access_type == 3" :label="$t('password')">
                            <Input v-model="article.access_value"></Input>
                        </FormItem>
                        <FormItem :label="$t('publish_at')">
                            <DatePicker type="datetime" @on-change="changePublish" format="yyyy-MM-dd HH:mm" v-model="article.publish_at"></DatePicker>
                        </FormItem>
                        <Button type="primary" @click="publish">
                            <span v-if="action==='add'">{{ $t('publish') }}</span>
                            <span v-else>{{ $t('update') }}</span>
                        </Button>
                    </div>
                </Panel>

            </Collapse>
            </Col>
        </Row>
        <Row style="margin-top: 16px">
            <Col span="24">
            <Collapse v-model="categoryShow">
                <Panel name="1">
                    {{ $t('categories') }}
                    <div slot="content">
                        <Tree ref="catTree" :data="treeData" show-checkbox multiple></Tree>

                    </div>
                </Panel>

            </Collapse>
            </Col>
        </Row>
        <Row style="margin-top: 16px">
            <Col span="24">
            <Collapse v-model="tagShow">
                <Panel name="1">
                    {{ $t('tags') }}
                    <div slot="content">
                        <Select
                                v-model="tags"
                                filterable
                                remote
                                multiple
                                :remote-method="searchTags"
                                :loading="tagLoading">
                            <Option v-for="(option, index) in tagLists" :value="option.id" :key="index">{{option.name}}</Option>
                        </Select>
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
    import TreeSelect from '_c/tree-select'
    import 'mavon-editor/dist/css/index.css'
    import { addArticle, getArticle, updArticle } from '@/api/articles'
    import { categoryTree } from '@/api/categories'
    import { searchTags } from '@/api/tags'
    import { uploadImg } from '@/api/fileUpload'
    import Cropper from '@/components/cropper'
    import { traverTree } from '@/libs/tools'
    import moment from 'moment'
    import { mapMutations } from 'vuex'
    export default {
        name: 'write-article',

        components: {
            mavonEditor,
            TreeSelect,
            moment,
            Cropper
        },
        data () {
            return {
                treeSelected: [],
                treeData: [],
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
                    navigation: true, // 导航目录
                    alignleft: true, // 左对齐
                    aligncenter: true, // 居中
                    alignright: true, // 右对齐
                    subfield: true, // 单双栏模式
                    preview: true, // 预览
                },
                publishBlock: '1',
                categoryShow: '1',
                tagShow: '1',
                cover: '1',
                article: {
                    title: '', //文章标题
                    descriptions: '', //文章描述
                    content: '',//文章内容markdown
                    content_html: '',//文章内容html
                    cover_image: '', //文章封面
                    category_id: 0, //分类
                    enable: 1, //状态
                    access_type: 1, //公开度
                    access_value: '', //密码
                    publish_at: '' //发布时间
                },
                tags: [],
                categories: [],
                setHeight: 0,
                img_file: {},
                imgSrc: '',
                tagLoading: false,
                tagLists: [],
                action: 'add'
            };
        },
        created: function () {
            window.addEventListener('resize', this.handleResize)
        },
        mounted: function () {

            categoryTree().then(res => {
                this.treeData = res.data.data;
                if(typeof this.$route.params.id !== 'undefined' && this.$route.params.id > 0) {
                    getArticle(this.$route.params.id).then(res => {

                        let articleData = res.data.data
                        if(parseInt(res.status) === 200) {
                            this.article = {
                                title: articleData.title, //文章标题
                                descriptions: articleData.descriptions, //文章描述
                                content: articleData.content,//文章内容
                                cover_image: articleData.cover_image, //文章封面
                                enable: articleData.enable, //状态
                                access_type: articleData.access_type, //公开度
                                access_value: articleData.access_value, //密码
                                publish_at: articleData.publish_at //发布时间
                            }

                            this.categories = articleData.categories;
                            traverTree(this.treeData, this.categories)

                            this.tagLists = articleData.tags;
                            this.tags = this.tagLists.map(item => {
                                return item.id;
                            });
                            this.action = 'update';
                        } else {
                            this.$Message.error('加载文章失败')
                        }
                    })
                }
            })
        },
        methods: {
            ...mapMutations([
                'closeTag'
            ]),
            handleResize() {
                this.setHeight = window.innerHeight - this.$refs.mdeditor.$el.clientHeight - 10;
            },
            changePublish(data) {
                this.article.publish_at = data;
            },
            publish() {
                if(this.article.publish_at.length <= 0) {
                    this.article.publish_at = moment().format('YYYY-MM-DD HH:mm')
                } else {
                    this.article.publish_at = moment(this.article.publish_at).format('YYYY-MM-DD HH:mm')
                }
                //检查必填项title,content,状态,公开度,发布时间,分类
                if(this.article.title.length <= 0) {
                    this.$Message.error({
                        content: '标题不能为空',
                        duration: 10,
                        closable: true
                    });
                    return false;
                }
                if(this.article.descriptions.length <= 0) {
                    this.$Message.error({
                        content: '描述不能为空',
                        duration: 10,
                        closable: true
                    });
                    return false;
                }
                if(this.article.content.length <= 0) {
                    this.$Message.error({
                        content: '内容不能为空',
                        duration: 10,
                        closable: true
                    });
                    return false;
                }
                this.article.content_html = this.$refs.mdeditor.d_render;
                if(this.article.access_type===3 && this.article.access_value.length <= 0) {
                    this.$Message.error({
                        content: '公开度为密码保护时，密码不能为空',
                        duration: 10,
                        closable: true
                    });
                    return false;
                }
                let categories = this.$refs.catTree.getCheckedAndIndeterminateNodes();
                let categoryIds = [];
                for (let i = 0; i < categories.length; i++) {
                    categoryIds.push(categories[i].id);
                }
                if(categoryIds.length <= 0) {
                    this.$Message.error({
                        content: '分类目录为必选项',
                        duration: 10,
                        closable: true
                    });
                    return false;
                }

                if(this.action === 'add') {
                    addArticle(this.article, {'categories':categoryIds},{'tags':this.tags}).then(res => {
                        if(parseInt(res.status) === 200) {
                            this.$Message.success('新增文章成功')
                            this.closeTag({
                                name: `write_article`
                            })
                            this.$router.push('/articles/index')
                        } else {
                            this.$Message.success('新增文章失败')
                        }
                    })
                } else {
                    updArticle(this.$route.params.id, this.article, {'categories':categoryIds},{'tags':this.tags}).then(res => {
                        if(parseInt(res.status) === 200) {
                            this.$Message.success('更新文章成功')
                            this.closeTag({
                                name: `write_article`,
                                params: {
                                    id: this.$route.params.id
                                }
                            })
                            this.$router.push('/articles/index')
                        } else {
                            this.$Message.success('更新文章失败')
                        }
                    })
                }

            },
            imgAdd(pos, file){
                let formData = new FormData();
                formData.append('image', file);
                uploadImg(formData).then(res => {
                    this.$refs.mdeditor.$img2Url(pos, res.data.data.path);
                })
                this.img_file[pos] = file;
            },
            imgDel(pos){
                delete this.img_file[pos];
            },
            handleCroped (blob) {
                const formData = new FormData()
                formData.append('image', blob)
                uploadImg(formData).then((res) => {
                    this.article.cover_image = res.data.data.path;
                    this.$Message.success('Upload success~')
                })
            },
            searchTags(query) {
                if(query.length >= 2) {
                    this.tagLoading = true;
                    searchTags(query).then(res => {
                        this.tagLoading = false;
                        if(parseInt(res.status) === 200) {
                            this.tagLists = res.data.data
                        } else {
                            this.tagLists = [];
                        }

                    })
                } else {
                    this.tagLists = [];
                }
            }
        }
    }

</script>
<style>

</style>