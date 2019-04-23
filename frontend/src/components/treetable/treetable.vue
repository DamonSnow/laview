<script src="../../libs/assist.js"></script>
<template>
    <Table
      :data="tableData"
      :columns="columnsTree"
      :stripe="stripe"
      :border="border"
      :show-header="showHeader"
      :width="width"
      :height="height"
      :loading="loading"
      :disabled-hover="disabledHover"
      :highlight-row="highlightRow"
      :row-class-name="rowClassName"
      :size="size"
      :no-data-text="noDataText"
      :no-filtered-data-text="noFilteredDataText"
      @on-current-change="onCurrentChange"
      @on-select="onSelect"
      @on-select-cancel="onSelectCancel"
      @on-select-all="onSelectAll"
      @on-selection-change="onSelectionChange"
      @on-sort-change="onSortChange"
      @on-filter-change="onFilterChange"
      @on-row-click="onRowClick"
      @on-row-dblclick="onRowDblclick"
      @on-expand="onExpand"
    >
    </Table>
</template>
<script>
 import TreeNode from './treenode.vue'
 import {deepCopy} from '../../libs/assist';
 export default {
  name: 'TreeTable',
  props: {
    data: {
      type: Array,
      default () {
        return []
      }
    },
    columns: {
      type: Array,
      default () {
        return []
      }
    },
    size: String,
    width: {
      type: [Number, String]
    },
    height: {
      type: [Number, String]
    },
    stripe: {
      type: Boolean,
      default: false
    },
    border: {
      type: Boolean,
      default: false
    },
    showHeader: {
      type: Boolean,
      default: true
    },
    highlightRow: {
      type: Boolean,
      default: true
    },
    rowClassName: {
      type: Function,
      default () {
        return ''
      }
    },
    context: {
      type: Object
    },
    noDataText: {
      type: String
    },
    noFilteredDataText: {
      type: String
    },
    disabledHover: {
      type: Boolean
    },
    loading: {
      type: Boolean,
      default: false
    },
    /**
     * @description 全局设置是否可编辑
     */
    editable: {
      type: Boolean,
      default: false
    },
    total: {
      type: [Number, String],
      default: 0
    },
    current: {
      type: [Number, String],
      default: 1
    },
    pageSize: {
      type: [Number, String],
      default: 10
    }
  },
  data () {
    return {
        columnsTree: [],
        tableData: this.makeTableData()
    }
  },
  methods: {
    onCurrentChange (currentRow, oldCurrentRow) {
      this.$emit('on-current-change', currentRow, oldCurrentRow)
    },
    onSelect (selection, row) {
      this.$emit('on-select', selection, row)
    },
    onSelectCancel (selection, row) {
      this.$emit('on-select-cancel', selection, row)
    },
    onSelectAll (selection) {
      this.$emit('on-select-all', selection)
    },
    onSelectionChange (selection) {
      this.$emit('on-selection-change', selection)
    },
    onSortChange (column, key, order) {
      this.$emit('on-sort-change', column, key, order)
    },
    onFilterChange (row) {
      this.$emit('on-filter-change', row)
    },
    onRowClick (row, index) {
      this.$emit('on-row-click', row, index)
    },
    onRowDblclick (row, index) {
      this.$emit('on-row-dblclick', row, index)
    },
    onExpand (row, status) {
      this.$emit('on-expand', row, status)
    },
    changePage (pageNumber) {
        this.$emit('on-change', pageNumber)
    },
    changePageSize (pageSize) {
        this.$emit('on-page-size-change', pageSize)
    },
    handleColumns (columns) {
      this.columnsTree = columns.map((item, index) => {
        let res = item
        if (res.type === 'treeNode') {
            res = this.renderTreeNode(res)
        }
        return res
      })
    },
    renderTreeNode (col) {
      let vm = this
      col.render = (h, params) => {
          return h(TreeNode, {
            props: {
              name: this.data[params.index][params.column.key],
              row: this.data[params.index]
            },
            on: {
              'on-toggle-expand': (row, isExpand) => {
                  let level = 2
                  if (row._level) {
                    level = row._level+1
                  }
                  vm.onToggleExpand(row, params.index, isExpand, level)
              },
              'on-toggle-checked': (row, isChecked) => {
                  let preIndex = params.index
                  let afterIndex = params.index
                  do {
                    this.tableData[afterIndex]._checked = isChecked
                    this.$set(this.tableData, afterIndex, this.tableData[afterIndex]) //使数组进行页面响应
                  } while (++afterIndex < this.tableData.length && row._level < this.tableData[afterIndex]._level)

                  if (!isChecked) {
                    let currlevel = row._level
                    while (--preIndex > -1) {
                      if (currlevel > this.tableData[preIndex]._level) {
                        this.tableData[preIndex]._checked = isChecked
                        this.$set(this.tableData, preIndex, this.tableData[preIndex]) //使数组进行页面响应
                      }
                    }
                  }
                  this.$emit('on-checked-change', this.tableData.filter((element, index, array) => {
                    return (element._checked === true)
                  }))

              }
            }
          })
      }
      return col
    },
    onToggleExpand (row, index, isExpand, level) {
      this.$emit('on-toggle-expand', row, index, isExpand)
      if (isExpand) {
         this.$emit('on-load-children', row, index, level, this.loadChildren)
      } else {
        if (row._level) {
          this.removeChildren(index, level)
        } else {
          this.removeChildren(index, 1)
        }
      }
    },
    removeChildren (rowIndex, level) {
      let startIndex = rowIndex;
      let rowLevel = (this.data[rowIndex]._level) ? this.data[rowIndex]._level: 1;
      let howmany = 0;
      while ((startIndex+howmany+1) < this.data.length && rowLevel < this.data[startIndex+howmany+1]._level) {
        howmany += 1
      }
      this.$emit('on-remove-children', startIndex+1, howmany)
    },
    loadChildren (index, level, res) {
        
      let rows = []
      this.data.forEach((item, m_index) => {
        if (m_index === index) {
          rows.push(item)
          //插入新数据
          res.map(function (item){
            item._level = level
            rows.push(item)
          })
        } else {
          rows.push(item)
        }
      })
      return rows

    },
    makeTableData () {
      return this.data.concat()
    }

  },
  watch: {
    columns (columns) {
      this.handleColumns(columns)
    },
    data (val) {
      this.tableData = this.makeTableData()
    }
  },
  mounted () {
    this.handleColumns(this.columns)
  }
}
</script>
