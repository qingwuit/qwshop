<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>
            <div class="admin_table_main">
                <el-table :data="list" @selection-change="handleSelectionChange">
                    <el-table-column type="selection"></el-table-column>
                    <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                    <el-table-column prop="id" label="#"  width="70px"></el-table-column>
                    <el-table-column prop="order_no" label="订单号"></el-table-column>
                    <el-table-column prop="nickname" label="用户名"></el-table-column>
                    <el-table-column label="操作" fixed="right" width="120px">
                        <template slot-scope="scope">
                            <el-button icon="el-icon-edit" @click="$router.push('/Seller/order/info/'+scope.row.order_id)">查看订单</el-button>
                            <!-- <el-button type="danger" icon="el-icon-delete" @click="del(scope.row.id)">删除</el-button> -->
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          list:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_order_list:function(){
            this.$post(this.$api.getSellerGroupbuyUserList,{gb_id:this.$route.params.gb_id}).then(res=>{
                this.list = res.data;
            })

        },

    },
    created() {
        this.get_order_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

.table_dl_dd_all{
    height: 50px;
    overflow: hidden;
}
</style>