<template>
  <div>

    <Modal
      ref="showSchedule"
      v-model="showSchedule"
      title="Title"
      :loading="loading"
      draggable>

      <h3 slot="header">{{schedule.title}}  <Tag :color="schedule.backgroundColor">{{schedule.calendar}}</Tag></h3>
        <small>{{schedule.start}} - {{schedule.end}}</small>
        <p>{{ schedule.body }}</p>
      <div slot="footer">
        <Button type="info" size="small" @click="handleEdit">Edit</Button>
        <Button type="error" size="small" @click="handleDelete">Delete</Button>
      </div>
    </Modal>
  </div>
</template>

<script>
  import moment from 'moment'

  export default {
    name: 'show-schedule-madel',

    props: {
      addModal: Boolean,
      calendarList: Array
    },
    data() {
      return {
        editModel: false,
        showSchedule: this.addModal,
        loading: false,
        schedule: {
            id: 0,
            calendarId: 0,
            title: '',
            body: '',
            category: '',
            start: '',
            end: '',
            backgroundColor: '',
            borderColor: '',
            textColor: '#FFFFFF',
            calendar: ''
        },

      }
    },

    methods: {
      handleEdit () {
        this.$emit('editEvent',this.schedule)
        this.showSchedule = false;
      },
      handleDelete () {
          let _this = this;
          this.showSchedule = false;
          this.$Modal.confirm({
              title: '删除计划',
              content: '你确定要删除计划:['+this.schedule.title+']？',
              'z-index': 99999,
              onOk: () => {
                  _this.$emit('delEvent',_this.schedule)
//                _this.tuiCalendar.invoke('deleteSchedule', String(_this.schedule.id), String(_this.schedule.calendarId));
              },
              onCancel: () => {
                  this.$Message.info('Clicked cancel');

              }
          })
      },
      open (event) {
        this.schedule = event;
        this.schedule.id = event.id;
        this.schedule.calendarId = event.calendarId;
        this.schedule.title = event.title;
        this.schedule.body = event.body;
        this.schedule.category = event.category;
        this.schedule.start = moment(event.start).format("YYYY-MM-DD HH:mm:ss");
        this.schedule.end = moment(event.end).format("YYYY-MM-DD HH:mm:ss");
        this.calendarList.forEach(item => {
            if(parseInt(item['id']) === parseInt(event.calendarId)) {
                this.schedule.backgroundColor = item['bgColor'];
                this.schedule.borderColor = item['borderColor'];
                this.schedule.calendar = item['name'];
            }
        })
        this.showSchedule = true;
      }
    }

  }
</script>