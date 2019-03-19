<template>
    <div class="lunarFullCalendar">
        <div class="full-calendar">
            <lunar-full-calendar :events="events"
                                 ref="calendar"
                                 @event-selected="eventSelected"
                                 :config="config"
                                 @day-click="dayClick"></lunar-full-calendar>
        </div>

    </div>
</template>
<script>
    import { LunarFullCalendar } from 'vue-lunar-full-calendar'
    import './index.less'
    export default {
        data: function () {
            let self = this
            return {
                events: [
                    {
                        id: 1,
                        title: '数据1',
                        allDay: true,
                        start: new Date()
                    },
                    {
                        id: 2,
                        title: '数据2',
                        start: new Date().getTime() + 24 * 60 * 60 * 1000,
                        end: new Date().getTime() + 2 * 24 * 60 * 60 * 1000
                    },
                    {
                        id: 3,
                        title: '数据3',
                        start: new Date().getTime() - 3 * 24 * 60 * 60 * 1000
                    },
                    {
                        id: 4,
                        title: '数据4（增加中国农历、24节气和节假日的问题）',
                        start: new Date(),
                        end: new Date().getTime() + 30 * 24 * 60 * 60 * 1000
                    },
                    {
                        id: 5,
                        title: '数据5（Increase the functions of Chinese lunar calendar, 24 solar terms and holidays）',
                        start: new Date(),
                        end: new Date().getTime() + 30 * 24 * 60 * 60 * 1000
                    },
                    {
                        id: 6,
                        title: '数据6（增加中国农历、24节气和节假日的问题Increase the functions of Chinese lunar calendar, 24 solar terms and holidays）',
                        start: new Date() - 30 * 24 * 60 * 60 * 1000,
                        end: new Date().getTime()
                    }
                ],
                config: {
                    // lunarCalendar
                    // Control whether the Chinese calendar shows true, unrealistic flase, default true.（lunarCalendar控制是否显示中国农历、显示的为true，隐藏为flase，默认为true）
                    lunarCalendar: true,
                    height: 'parent',
                    locale: 'zh-cn',
                    buttonText: {
                        today: '今天',
                        month: '月',
                        week: '周',
                        day: '日'
                    },
                    header: {
                        left: 'prev,next, today',
                        center: 'title',
                        right: 'hide, custom, month,agendaWeek,agendaDay'
                    },
                    // eventOrder:'index',   // 这个是控制事件排序的功能，意思是 按照字段 事件数据中index来排序
                    // eventLimitClick: 'day', //点击今天日列表图
                    eventLimit: true, // 一天中显示多少条事件，多了隐藏
                    firstDay: 0, // 控制周一周日那个在前面
                    defaultView: 'month',
                    allDaySlot: true, // agenda视图下是否显示all-day
                    allDayText: '全天', // agenda视图下all-day的显示文本
                    timezone: 'local', // 时区默认本地的
                    slotLabelFormat: 'HH:mm', // 周视图和日视同的左侧时间显示
                    viewRender (view, element) {
                        self.viewRender(view, element)
                    },
                    customButtons: { // 新增按钮
                        hide: {
                            text: '隐藏农历',
                            click: function () {
                                self.$refs.calendar.fireMethod('option', {
                                    lunarCalendar: false
                                })
                                self.$refs.calendar.fireMethod('option', {
                                    header: {
                                        left: 'prev,next, today',
                                        center: 'title',
                                        right: 'show, custom, month,agendaWeek,agendaDay'
                                    }
                                })
                            }
                        },
                        show: {
                            text: '显示农历',
                            click: function () {
                                self.$refs.calendar.fireMethod('option', {
                                    lunarCalendar: true
                                })
                                self.$refs.calendar.fireMethod('option', {
                                    header: {
                                        left: 'prev,next, today',
                                        center: 'title',
                                        right: 'hide, custom, month,agendaWeek,agendaDay'
                                    }
                                })
                            }
                        }
                    }
                }
            }
        },
        components: {
            LunarFullCalendar
        },
        methods: {
            // 注释的是功能是可以修改对应的功能值属性，比如设置  eventLimit为 false
            //  this.$refs.calendar.fireMethod('option',{
            //      eventLimit :false
            //  })
            dayClick (date, jsEvent, view) { // 点击当天的事件
                alert('农历数据：' + JSON.stringify(window.lunar(date._d)))
                console.log(date, jsEvent, 'dayClick')
            },
            eventSelected (event, jsEvent, view) { // 选中事件
                console.log(event, jsEvent, 'eventSelected')
            },
            viewRender (view, element) {
                console.log(view, element, 'viewRender')
            }
        }
    }
</script>