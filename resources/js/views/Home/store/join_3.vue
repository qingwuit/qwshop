<template>
    <div class="join_3">
        <div class="shop_top"><shop-top :subnav_show="false" :change_color="true"></shop-top></div>
        
        <div class="join_over width_center_1200">
            <div class="join_bzt">
                <el-steps :active="4" simple finish-status="success">
                    <el-step title="提交入驻资料" icon="el-icon-edit"></el-step>
                    <el-step title="商家等待审核" icon="el-icon-unlock"></el-step>
                    <el-step title="完善店铺信息" icon="el-icon-tickets"></el-step>
                    <el-step title="店铺上线" icon="el-icon-goods"></el-step>
                </el-steps>
            </div>
        </div>
        <div class="join_over width_center_1200">
            <div class="join_over_title"><el-divider>审核通过</el-divider><p>AUDIT PASS</p></div>
            <div class="join_pass">
                
            </div> 
            <div class="join_btn_next" @click="$router.push('/Seller/login')">
                前往商家后台
            </div>
        </div>
        <shop-foot></shop-foot>
    </div>
</template>

<script>
import ShopTop from "@/components/home/public/head.vue"
import ShopFoot from "@/components/home/public/shop_foot.vue"
export default {
    components: {
        ShopTop,
        ShopFoot,
    },
    data() {
      return {
          info:{
          },
      };
    },
    methods: {
        // 获取当前入驻状态
        get_store_info:function(){
            let url = this.$api.homeStoreJoin;
            this.$get(url).then(res=>{
                this.info = res.data.store_info;
                if(!this.$isEmpty(this.info)){
                    if(this.info.store_verify == 2){  // 申请失败
                        this.$message.error('申请失败，请询问工作人员再次申请！');
                        return this.$router.push('/store/join_2');
                    }else if(this.info.store_verify == 1){ // 申请成功
                        return this.$router.push('/store/join_3');
                    }else if(this.info.store_verify == 0){
                        return this.$router.push('/store/join_4');
                    }
                }
            });
        },
  
    },
    created() {
        this.get_store_info();
    },
};
</script>
<style lang="scss" scoped>

.join_over_title{
    padding-top:40px;
    width: 300px;
    text-align: center;
    margin:0 auto;
    .el-divider__text{
        font-size: 22px;
    }
    p{
        font-size: 12px;
        color:#999;
    }
}
.join_bzt{
    margin-top:50px;
}
.join_pass{
    padding:20px;
   
}
.join_btn_next{
    margin:0 auto;
    margin-bottom: 40px;
    margin-top:30px;
    line-height: 40px;
    background: #ca151e;
    width: 120px;
    text-align: center;
    color:#fff;
    font-size: 14px;
}
</style>