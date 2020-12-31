<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            全国地址编辑
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="所属地区">
                    <a-tree-select  v-model="info.pid">
                        <a-tree-select-node title="顶级地区" :value="0"></a-tree-select-node>
                        <template v-for="(v,k) in list">
                            <a-tree-select-node v-if="v.id != info.id" :title="v.name" :value="v.code" :key="k">
                                <template v-for="(vo,key) in v.children">
                                <a-tree-select-node v-if="vo.id != info.id" :key="key" :title="vo.name" :value="vo.code"></a-tree-select-node>
                                </template>
                            </a-tree-select-node>
                        </template>
                        
                    </a-tree-select>
                </a-form-model-item>
                <a-form-model-item label="地址名称">
                    <a-input v-model="info.name"></a-input>
                </a-form-model-item>
                <a-form-model-item label="地址编号">
                    <a-input v-model="info.code"></a-input>
                </a-form-model-item>
                <a-form-model-item label="级别">
                    <a-select v-model="info.deep">
                        <a-select-option :value="0">省份</a-select-option>
                        <a-select-option :value="1">城市</a-select-option>
                        <a-select-option :value="2">区县</a-select-option>
                    </a-select>
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
              deep:0,
              pid:0,
          },
          list:[],
          id:0,
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){

            // 验证代码处
            if(this.$isEmpty(this.info.name)){
                return this.$message.error('地区名不能为空');
            }

            
            let api = this.$apiHandle(this.$api.adminAreas,this.id);
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
            this.$get(this.$api.adminAreas+'/'+this.id).then(res=>{
                this.info = res.data;
            })
        },
        // 获取菜单列表
        onload(){

            // 判断你是否是编辑
            if(!this.$isEmpty(this.$route.params.id)){
                this.id = this.$route.params.id;
                this.get_info();
            }

            this.$get(this.$api.adminAreas).then(res=>{
                this.list = res.data;
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