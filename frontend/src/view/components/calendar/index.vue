<template>
    <div class="lunarFullCalendar">
        <!-- 新增计划modal -->
        <createSchedule ref="createSchedule" :addModal="addModal" :calendarList="calendarList" @newEvent="newEvent"></createSchedule>
        <!-- 显示计划详情modal -->
        <showSchedule ref="showSchedule" :addModal="addModal" :calendarList="calendarList" @delEvent="delEvent" @editEvent="editEvent"></showSchedule>
        <!-- 更新计划modal -->
        <editSchedule ref="editSchedule" :addModal="addModal" :calendarList="calendarList" @updEvent="updEvent"></editSchedule>
        <div class="full-calendar">
            <lunar-full-calendar :events="events"
                                 ref="calendar"
                                 @event-selected="eventSelected"
                                 @event-drop="eventDrop"
                                 :config="config"
                                 @day-click="dayClick"></lunar-full-calendar>
        </div>

    </div>
</template>
<script>
    import { LunarFullCalendar } from 'vue-lunar-full-calendar'
    import { calendars,getSchedules,updScheTime,delSchedule } from '@/api/schedule'
    import moment from 'moment'
    import createSchedule from './components/create-shcedule.vue'
    import showSchedule from './components/show-shcedule.vue'
    import editSchedule from './components/edit-shcedule.vue'
    import './index.less'
    export default {
        name: 'myLunarCalendar',
        components: {
            LunarFullCalendar,
            createSchedule,
            showSchedule,
            editSchedule
        },
        data: function () {
            let self = this
            return {
                addModal: false,
                view: 'month',
                calendarList: [],
                events: [],
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
                    nextDayThreshold: 0,
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
        mounted: function () {
            calendars().then(res => {
                this.calendarList = res.data.data
            })
        },
        methods: {

            dayClick (date, jsEvent, view) { // 点击当天的事件


                this.$refs.createSchedule.open(moment(date._d).format('YYYY-MM-DD HH:mm:ss'),moment(date._d).add('1','h').format('YYYY-MM-DD HH:mm:ss'));
            },
            eventSelected (event, jsEvent, view) { // 选中事件
                console.log(event, jsEvent, view)
                this.$refs.showSchedule.open(event);
            },
            viewRender (view, element) {
                console.log(view, element, 'viewRender')
                this.view = view.type;
                this.setRenderRangeText(moment(view.start).format('YYYY-MM-DD HH:mm:ss'), moment(view.end).format('YYYY-MM-DD HH:mm:ss'));
            },
            eventDrop(event) {
                console.log(event);
            },
            setRenderRangeText(start, end) {
                let condition = {start: start,end: end,calendar_id: null}
                let viewName = this.view;
                let html = [];
                if (viewName === 'month') {
                    console.log('month')
                } else if (viewName === 'agendaWeek') {
                    console.log('week')
                } else {
                    console.log('day')
                }
                this.axiosGetSchedules(condition);
            },
            axiosGetSchedules (condition) {
                let scheduleData = [];
                getSchedules(condition).then(res => {
                    console.log(res.data)
                    res.data.data.forEach(item => {
                        scheduleData.push({
                            id: item.id,
                            calendarId: String(item.calendarId),
                            title: item.title,
                            body: item.body,
                            category: item.category,
                            dueDateClass: '',
                            start: item.start,
                            end: item.end,
                            backgroundColor: item.backgroundColor,
                            borderColor: item.borderColor,
                            textColor: '#FFFFFF'
                        })
                    })
                    this.events = scheduleData;
                })
            },
            newEvent(event) {
                event.backgroundColor = 'yellow';
                event.borderColor = 'yellow';
                event.textColor = '#FFFFFF';
                this.calendarList.forEach(item => {
                    if(parseInt(item.id) === parseInt(event.calendar_id)) {
                        event.backgroundColor = item.bgColor;
                        event.borderColor = item.borderColor;
                        event.textColor = '#FFFFFF';
                    }
                })
                console.log(event);
                this.events.push(event);
            },
            delEvent(event) {
                let _this = this;
                delSchedule(event.id).then(res => {
                    if (parseInt(res.data.code) === 200) {
                        _this.$refs.calendar.fireMethod('removeEvents',[event.id]);
                    } else {
                        _this.$Message.error(res.data.message);
                    }
                }).catch(function (error) {
                    _this.$Message.error(error.response.data.message);
                })
            },
            editEvent(event) {
                this.$refs.editSchedule.open(event);
            },
            updEvent(event) {
                console.log(event)
                event.backgroundColor = 'yellow';
                event.borderColor = 'yellow';
                event.textColor = '#FFFFFF';
                this.calendarList.forEach(item => {
                    if(parseInt(item.id) === parseInt(event.calendar_id)) {
                        event.backgroundColor = item.bgColor;
                        event.borderColor = item.borderColor;
                        event.textColor = '#FFFFFF';
                    }
                })
                this.events = this.events.filter(item => {
                    return parseInt(item.id) != parseInt(event.id)
                })
                this.events.push(event);
            }
        }
    }
</script>