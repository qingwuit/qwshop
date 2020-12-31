<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            商品分类编辑
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="所属菜单">
                    <a-tree-select tree-default-expand-all v-model="info.pid">
                        <a-tree-select-node title="顶级菜单" :value="0"></a-tree-select-node>
                        <template v-for="(v,k) in list">
                            <a-tree-select-node v-if="v.id != id" :title="v.name" :value="v.id" :key="k">
                                <template v-for="(vo,key) in v.children">
                                <a-tree-select-node v-if="vo.id != id" :key="key" :title="vo.name" :value="vo.id"></a-tree-select-node>
                                </template>
                            </a-tree-select-node>
                        </template>
                        
                    </a-tree-select>
                </a-form-model-item>
                <a-form-model-item label="分类名称">
                    <a-input v-model="info.name"></a-input>
                </a-form-model-item>
                <a-form-model-item label="图标">
                    <a-upload
                        list-type="picture-card"
                        class="avatar-uploader"
                        :show-upload-list="false"
                        :action="$api.adminGoodsClassesUploadThumb"
                        :data="{token:$getSession('token_type')}"
                        @change="upload"
                    >
                        <img v-if="info.thumb" :src="info.thumb" />
                        <div v-else>
                            <a-font v-if="!loading" type='iconplus' />
                            <a-icon v-else type="loading" />
                        </div>
                    </a-upload>
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
              pid:0,
          },
          list:[],
          id:0,
          loading:false,
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){

            // 验证代码处
            if(this.$isEmpty(this.info.name)){
                return this.$message.error('分类名不能为空');
            }

            
            let api = this.$apiHandle(this.$api.adminGoodsClasses,this.id);
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
            this.$get(this.$api.adminGoodsClasses+'/'+this.id).then(res=>{
                this.info = res.data;
            })
        },
        // 获取菜单列表
        onload(){
            let is_type = this.$route.query.is_type;
            let params = {};
            if(!this.$isEmpty(is_type)){
                params.is_type = is_type;
                this.info.is_type = parseInt(is_type);
            }

            // 判断你是否是编辑
            if(!this.$isEmpty(this.$route.params.id)){
                this.id = this.$route.params.id;
                this.get_info();
            }

            this.$get(this.$api.adminGoodsClasses,params).then(res=>{
                this.list = res.data;
            });
        },
        upload(e){
            if(e.file.status == 'done'){
                this.loading = false;
                let rs = e.file.response;
                if(rs.code == 200){
                    this.$set(this.info,'thumb',rs.data);
                    this.info.thumb = rs.data;
                }else{
                    return this.$message.error(rs.msg);
                }
            }else{
                this.loading = true;
            }
            
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