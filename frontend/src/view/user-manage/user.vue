<template>
  <div>
    <Card>
      <Table :columns="columns" :data="data" :loading="loading" border size="small"></Table>

        <div style="text-align: center;margin: 16px 0">
          <Page
            :total="total"
            :current.sync="current"
            show-sizer
            @on-change="getData"
            @on-page-size-change="handleChangeSize"
          ></Page>
      </div>

    </Card>
  </div>
</template>

<script>
import { users } from '@/api/user'
export default {
  data () {
    return {
      columns: [
        {
          title: '工号',
          key: 'job_number'
        },
        {
          title: '名称',
          key: 'name'
        },
        {
          title: '邮箱',
          key: 'email'
        },
        {
          title: '手机',
          key: 'phone'
        }
      ],
      data: [],
      loading: false,
      total: 0,
      current: 1,
      size: 10
    }
  },
  methods: {
    getData () {
      if (this.loading) return

      this.loading = true

      users().then(res => {
        console.log(res.data.data)
        this.data = res.data.data
        this.loading = false
      })
    },
    handleChangeSize () {
      this.getData()
    }
  },
  mounted: function () {
    this.getData()
  }
}
</script>
