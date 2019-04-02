<template>
  <div>
    <Modal
      ref="editSchedule"
      v-model="showModal"
      title="Title"
      :loading="loading"
      draggable
      @on-ok="handleSubmit('scheduleForm')"
      @on-cancel="handleReset('scheduleForm')">
      <p slot="header">{{ $t('update-schedule') }}</p>
      <Form ref="scheduleForm" :model="schedule" :rules="ruleValidate" :label-width="90">
        <FormItem :label='$t("calendar")' prop="calendarId">
          <Select v-model="schedule.calendarId">
            <Option v-for="calendar in calendarList" :value="calendar.id" :key="calendar.id">{{ calendar.name }}</Option>
          </Select>
        </FormItem>
        <FormItem :label="$t('title')" prop="title">
          <Input v-model="schedule.title" :placeholder='$t("Enter title")'></Input>
        </FormItem>
        <FormItem label="Text" prop="body">
          <Input v-model="schedule.body" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Enter something..."></Input>
        </FormItem>
        <FormItem :label="$t('category')" prop="category">

            <Select v-model="schedule.category">
                <Option value="0">time</Option>
                <Option value="1">allday</Option>
                <Option value="2">task</Option>
                <Option value="3">mile stone</Option>
            </Select>
        </FormItem>
        <FormItem :label="$t('timeRange')" prop="timeRange">
          <DatePicker type="datetimerange" v-model="schedule.timeRange" format="yyyy-MM-dd HH:mm" placeholder="请选择时间" style="width: 300px"></DatePicker>
          <Checkbox v-model="schedule.allDay" style="margin-left: 10px">All Day</Checkbox>
        </FormItem>

      </Form>
    </Modal>
  </div>
</template>

<script>
  import {updSchedule} from '@/api/schedule'
  import moment from 'moment'
  export default {
    name: 'edit-schedule-madel',
    props: {
      addModal: Boolean,
      calendarList: Array
    },
    data() {
      return {
        showModal: this.addModal,
        loading: false,
        schedule: {
          id: 0,
          calendarId: 0,
          title: '',
          body: '',
          category: '',
          timeRange: '',
          allDay: false,
        },

        ruleValidate: {
          calendarId: [
            { required: true, type: 'number', message: 'The calendar id cannot be empty', trigger: 'change' }
          ],
          title: [
            { required: true, message: 'The title cannot be empty', trigger: 'blur' }
          ],
          body: [
            { required: true, message: 'The body cannot be empty', trigger: 'blur' }
          ],
          category: [
            { required: true, message: 'The category cannot be empty', trigger: 'change' }
          ],
          timeRange: [{
              type: 'array',
              required: true,
              fields: {
                  0: {type: 'date', required: true, message: '请输入起止日期'},
                  1: {type: 'date', required: true, message: '请输入起止日期'}
              }
          }],
        },
      }
    },

    methods: {
      handleSubmit (name) {
        let _this = this;
        this.$refs[name].validate((valid) => {
          if (valid) {
              let data = {
                  'calendar_id' : _this.schedule.calendarId,
                  'title'       : _this.schedule.title,
                  'body'        : _this.schedule.body,
                  'start'       : moment(_this.schedule.timeRange[0]).format("YYYY-MM-DD HH:mm:ss"),
                  'end'         : moment(_this.schedule.timeRange[1]).format("YYYY-MM-DD HH:mm:ss"),
                  'category'    : _this.schedule.category,
                  'is_all_day'  : _this.schedule.allDay,
                  'status'      : 0,
              }

            updSchedule(_this.schedule.id, data).then(res => {
                console.log(res.data.data)
              if (parseInt(res.data.code) === 200) {
                this.$Message.success('更新计划成功');
                data.id = _this.schedule.id;
                _this.$emit('updEvent',data)
                _this.handleReset('scheduleForm')
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                this.showModal = true;
              }
            }).catch(function (error) {
                console.log(error)
              _this.showModal = true;
              _this.$Message.error(error.response.data.message);
            })
          } else {
            _this.$refs.editSchedule.visible = true;
            _this.showModal = true;
          }
        })


      },
      handleReset (name) {
        this.$refs[name].resetFields();

      },
      open (schedule) {
        console.log(schedule)
        this.schedule.id = schedule.id;
        this.schedule.calendarId = parseInt(schedule.calendarId);
        this.schedule.title = schedule.title;
        this.schedule.body = schedule.body;
        switch (schedule.category) {
            case 'time':
                this.schedule.category = '0';
                break;
            case 'allday':
                this.schedule.category = '1';
                break;
            case 'task':
                this.schedule.category = '2';
                break;
            case 'milestone':
                this.schedule.category = '3';
                break;
            default:
                this.schedule.category = '0';
        }
        this.schedule.allDay = schedule.allDay;
        this.schedule.timeRange = moment(schedule.start).format('YYYY-MM-DD HH:mm:ss') + ' - ' + moment(schedule.end).format('YYYY-MM-DD HH:mm:ss')
        this.showModal = true;
      }
    }

  }
</script>