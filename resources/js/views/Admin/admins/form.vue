<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            管理员编辑
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="用户名">
                    <a-input v-model="info.username"></a-input>
                </a-form-model-item>
                <a-form-model-item label="密码">
                    <a-input type="password" v-model="info.password" placeholder=""></a-input>
                </a-form-model-item>
                <a-form-model-item label="昵称">
                    <a-input v-model="info.nickname"></a-input>
                </a-form-model-item>
                <a-form-model-item label="管理员角色">
                    <a-tree-select 
                    :replace-fields="{children:'children', title:'name', key:'id', value: 'id' }" 
                    v-model="info.role_id" 
                    :tree-data="list" 
                    tree-checkable 
                    :show-checked-strategy="SHOW_PARENT"></a-tree-select>
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
          info:{
          },
          id:0,
          list:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){

            // 验证代码处
            if(this.$isEmpty(this.info.username)){
                return this.$message.error('用户名不能为空');
            }

            let api = this.$apiHandle(this.$api.adminAdmins,this.id);
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
            this.$get(this.$api.adminAdmins+'/'+this.id).then(res=>{
                res.data.password = undefined;
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
            this.$get(this.$api.adminRoles,{per_page:100}).then(res=>{
                this.list = res.data.data;
            });
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