<template>
  <div>
    <Card>
      <Table :columns="columns" :data="data" :loading="loading" border size="small" @on-sort-change="handleSortChange"></Table>

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
          type: 'index',
          width: 60,
          align: 'center',
          indexMethod: (row) => {
            return (row._index + 1) + (this.size * this.current) - this.size;
          }
        },
        {
          title: '工号',
          key: 'job_number',
          sortable: 'custom'
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
      size: 10,
      sortType: 'normal', //normal asc desc
    }
  },
  methods: {
    getData () {
      if (this.loading) return

      this.loading = true

      users(this.current, this.size, this.sortType).then(res => {
        console.log(res.data)
        this.data = res.data.data;
        this.total = res.data.meta.total;
        this.loading = false;
      })
    },
    handleChangeSize (val) {
      this.size = val;
      this.$nextTick(() => {
        this.getData();
      })
    },
    handleSortChange ({ columns, key, order }) {
      this.sortType = order;
      this.current = 1;
      this.getData();
    }
  },
  mounted: function () {
    this.getData()
  }
}
</script>
