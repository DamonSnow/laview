<template>
    <div>
        <span style="cursor: pointer;" @click="clickTreeNode">
            <span :style="offset"><Icon :type="nodeClass" size="14" /></span>
        </span>
        <Checkbox
            :indeterminate="false"
            :value="row._checked || false"
            @on-change="toggleChecked">{{name}}</Checkbox>
    </div>
</template>
<script>
export default {
        props: {
            name: String,
            row : null
        },
        data () {
            return {
            	treeNodeClass: 'md-arrow-dropright',
            };
        },
        methods: {
            clickTreeNode () {
                if (this.row._isExpand) {
                	this.row._isExpand = false
                    this.$emit('on-toggle-expand', this.row, false)
                } else {
                	this.row._isExpand = true
                    this.$emit('on-toggle-expand', this.row, true)
                }
            },
            toggleChecked (isChecked) {
                this.$emit('on-toggle-checked', this.row, isChecked)
            }
        },
        computed: {
			// 计算属性的 getter
			offset: function () {
			  // `this` 指向 vm 实例
			  return 'margin-right: 5px; margin-left: '+20*(this.row._level || 1)+'px'
			},
			nodeClass: function() {
				//debugger
				return this.row._isExpand ? 'md-arrow-dropdown': 'md-arrow-dropright'
			}

		}
    };
</script>
