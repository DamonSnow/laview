<style scoped>
    .layout{
        border: 1px solid #d7dde4;
        background: #f5f7f9;
        position: relative;
        border-radius: 4px;
        overflow: hidden;
    }
    .layout-logo{
        width: 300px;
        height: 30px;
        border-radius: 3px;
        float: left;
        position: relative;
        top: 15px;
        left: 20px;
    }
    .layout-nav{
        width: 420px;
        margin: 0 auto;
        margin-right: 20px;
    }
    .layout-footer-center{
        text-align: center;
    }
</style>
<template>
    <div class="layout">
        <Layout>
            <Header>
                <Menu mode="horizontal" theme="dark" active-name="1">
                    <div class="layout-logo">
                        <!--<img src="./assets/images/banner.png" style="width: 100%;height: 100%" alt="">-->
                        <vue-typer
                                :text='["Arya Stark","Jon Snow","Daenerys Targaryen","Melisandre","Tyrion Lannister"]'
                                :repeat='Infinity'
                                :shuffle='true'
                                initial-action='typing'
                                :pre-type-delay='70'
                                :type-delay='80'
                                :pre-erase-delay='2000'
                                :erase-delay='50'
                                erase-style='backspace'
                                :erase-on-complete='true'
                                caret-animation='smooth'
                        ></vue-typer>
                    </div>
                    <div class="layout-nav">
                        <MenuItem name="1">
                            <Icon type="ios-navigate"></Icon>
                            Item 1
                        </MenuItem>
                        <MenuItem name="2">
                            <Icon type="ios-keypad"></Icon>
                            Item 2
                        </MenuItem>
                        <MenuItem name="3">
                            <Icon type="ios-analytics"></Icon>
                            Item 3
                        </MenuItem>
                        <MenuItem name="4">
                            <Icon type="ios-paper"></Icon>
                            Item 4
                        </MenuItem>
                    </div>
                </Menu>
            </Header>
            <Content :style="{padding: '0 50px'}">
                <Row style="margin-top: 16px;">
                    <Col span="18">
                    <router-view></router-view>
                    </Col>
                    <Col span="6">
                        类别，标签等
                    </Col>
                </Row>

            </Content>
            <Footer class="layout-footer-center">2011-2016 &copy; TalkingData</Footer>
        </Layout>
    </div>
</template>
<script>
    import { VueTyper } from 'vue-typer'
    export default {
        components: {
            VueTyper
        }
    }
</script>
<style>

    .vue-typer {
        font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
        font-size: 20px;
    }
    .vue-typer .custom.char.typed {
        color: #009688;
    }
    .vue-typer .custom.char.selected {
        color: #E91E63;
    }

    .vue-typer .custom.caret {
        animation: rocking 1s ease-in-out 0s infinite;
        front-size: 20px;
    }
    .vue-typer .custom.caret.typing {
        background-color: #009688;
    }
    .vue-typer .custom.caret.selecting {
        display: inline-block;
        background-color: #E91E63;
    }
</style>
