<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            满减编辑
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="满减名称" :rules="{ required: true}">
                    <a-input v-model="info.name" />
                </a-form-model-item>
                <a-form-model-item label="满减额度" :rules="{ required: true}">
                    <a-input suffix="￥" type="number" v-model="info.money" />
                </a-form-model-item>
                <a-form-model-item label="消费金额" :rules="{ required: true}">
                    <a-input suffix="￥" type="number" v-model="info.use_money" />
                </a-form-model-item>
                <a-form-model-item label="截止时间" :rules="{ required: true}">
                    <a-range-picker v-model="info.times"  />
                </a-form-model-item>
    
                <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }">
                    <a-button type="primary" @click="handleSubmit">提交</a-button>
                </a-form-model-item>
            </a-form-model>
            
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          info:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        onChange(e){
            console.log(e)
        },
        handleSubmit(){

            // 验证代码处
            if(this.$isEmpty(this.info.name)){
                return this.$message.error('名称不能为空');
            }

            if(this.$isEmpty(this.info.times)){
                return this.$message.error('时间不能为空');
            }

            this.info.times[0] = moment(this.info.times[0]).format('YYYY-MM-DD hh:mm:ss')
            this.info.times[1] = moment(this.info.times[1]).format('YYYY-MM-DD hh:mm:ss')
            let api = this.$apiHandle(this.$api.sellerFullReductions,this.id);
            if(api.status){
                this.$put(api.url,this.info).then(res=>{
                    if(res.code == 200){
                        this.$message.success(res.msg)
                        return this.$router.back();
                    }else{
                        return this.$message.error(res.msg)
                    }
                })
            }else{
                this.$post(api.url,this.info).then(res=>{
                    if(res.code == 200){
                        this.$message.success(res.msg)
                        return this.$router.back();
                    }else{
                        return this.$message.error(res.msg)
                    }
                })
            }
   
            
        },
        get_info(){
            this.$get(this.$api.sellerFullReductions+'/'+this.id).then(res=>{
                this.info = res.data;
            })
        },
        // 获取列表
        onload(){
            // 判断你是否是编辑
            if(!this.$isEmpty(this.$route.params.id)){
                this.id = this.$route.params.id;
                this.get_info();
            }
 
        },
  
        
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>