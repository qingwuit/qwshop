<template>
    <div class="qingwu">
        <div class="admin_table_page_title">商品分类管理</div>
        <div class="unline underm"></div>

        <div class="admin_table_handle_btn">
            <a-button @click="$router.push('/Admin/goods_classes/form')" type="primary" icon="plus">添加</a-button>
            <a-button @click="clear_cache"><a-font type="iconitemno_0"></a-font>清除缓存</a-button>
            <a-button class="admin_delete_btn" type="danger" icon="delete" @click="del">批量删除</a-button>
        </div>
        <div class="admin_table_list">
            <a-table :columns="columns" :data-source="list" :pagination="false" @expand="expanded" :loading="loading" :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }" row-key="id">
                <span slot="expand"><!-- -> --></span>
                <span slot="name" slot-scope="rows">
                    <div class="admin_pic_txt">
                        <div class="img"><img v-if="rows.thumb" :src="rows.thumb"><a-icon v-else type="picture" /></div>
                        <div class="text">{{rows.name}}</div>
                        <div class="clear"></div>
                    </div>
                </span>
                <span slot="is_sort" slot-scope="rows">
                    <a-input type="number" @blur="sortChange(rows)" v-model="rows.is_sort" />
                </span>
                <span slot="action" slot-scope="rows">
                    <a-button icon="edit" @click="$router.push('/Admin/goods_classes/form/'+rows.id)">编辑</a-button>
                </span>
            </a-table>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          selectedRowKeys:[], // 被选择的行
          columns:[
              {title:'#',key:'id',fixed:'left',scopedSlots: { customRender: 'expand' }},
              {title:'分类名称',key:'id',fixed:'left',scopedSlots: { customRender: 'name' }},
              {title:'排序',fixed:'right',width:'120px',scopedSlots: { customRender: 'is_sort' }},
              {title:'操作',key:'id',fixed:'right',scopedSlots: { customRender: 'action' }},
          ],
          loading:false,
          list:[],
          classList:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 选择框被点击
        onSelectChange(selectedRowKeys) {
            this.selectedRowKeys = selectedRowKeys;
        },
        // 删除
        del(){
            if(this.selectedRowKeys.length==0){
                return this.$message.error('未选择数据.');
            }
            this.$confirm({
                title: '你确定要删除选择的数据？',
                content: '确定删除后无法恢复.',
                okText: '是',
                okType: 'danger',
                cancelText: '取消',
                onOk:()=> {
                    let ids = this.selectedRowKeys.join(',');
                    this.$delete(this.$api.adminGoodsClasses+'/'+ids).then(res=>{
                        if(res.code == 200){
                            this.onload();
                            this.$message.success('删除成功');
                        }else{
                            this.$message.error(res.msg)
                        }
                    });
                    
                },
            });
        },
        // 清空缓存
        clear_cache(){
            this.$get(this.$api.adminGoodsClassesClearCache).then(res=>{
                return this.$message.success(res.msg)
            });
        },
        onload(){
            this.loading = true;
            this.$get(this.$api.adminGoodsClasses).then(res=>{
                this.loading = false;
                this.classList = res.data;
                res.data.forEach(item=>{
                    let info = {};
                    info = {name:item.name,pid:item.pid,id:item.id,is_sort:item.is_sort,lev:item.lev,thumb:item.thumb,children:[]}
                    this.list.push(info);
                })
            });
        },
        // 展开再去获取数据防止卡住
        expanded(status,rows){
            if(rows.lev == 0){
                this.classList.forEach((items,key)=>{
                    if(items.id == rows.id){
                        this.list[key].children = items.children;
                    }
                })
            }
        },
        // 排序移动
        sortChange(rows){
            let api = this.$apiHandle(this.$api.adminGoodsClasses,rows.id);
            this.$put(api.url,rows).then(res=>{
                if(res.code == 200){
                    this.onload();
                    return this.$message.success(res.msg)
                }else{
                    return this.$message.error(res.msg)
                }
            })
            
        }
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>