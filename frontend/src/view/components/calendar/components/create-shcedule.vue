<template>
  <div>

    <Modal
      ref="createSchedule"
      v-model="showModal"
      title="Title"
      :loading="loading"
      draggable
      @on-ok="handleSubmit('scheduleForm')"
      @on-cancel="handleReset('scheduleForm')">
      <p slot="header">{{ $t('add-schedule') }}</p>
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
          <Checkbox v-model="schedule.isAllDay" style="margin-left: 10px">All Day</Checkbox>
        </FormItem>

      </Form>
    </Modal>
  </div>
</template>

<script>
  import {addSchedule} from '@/api/schedule'
  import moment from 'moment'
  export default {
    name: 'create-schedule-madel',
    props: {
      addModal: Boolean,
      calendarList: Array
    },
    data() {
      return {
        showModal: this.addModal,
        loading: false,
        schedule: {
          calendarId: 0,
          title: '',
          body: '',
          category: '',
          timeRange: '',
          isAllDay: false,
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
                  'is_all_day'  : _this.schedule.isAllDay,
                  'status'      : 0,
              }
            addSchedule(data).then(res => {

              if (parseInt(res.data.code) === 200) {
                this.$Message.success('新增计划成功');
                _this.$emit('newEvent',data)
                _this.handleReset('scheduleForm')
                _this.showModal = false;
              } else {
                this.$Message.error(res.data.message);
                this.showModal = true;
              }
            }).catch(function (error) {
              _this.showModal = true;
              _this.$Message.error(error.response.data.message);
              _this.handleReset('scheduleForm')
            })
          } else {
            _this.$refs.createSchedule.visible = true;
            _this.showModal = true;
          }
        })

      },
      handleReset (name) {
        this.$refs[name].resetFields();

      },
      open (start, end) {
        this.schedule.timeRange = start + ' - ' + end
        this.showModal = true;
      }
    }

  }
</script>