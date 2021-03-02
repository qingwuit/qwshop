<template>
    <div class="user_address">
        <div class="user_main">
            <div class="block_title">
                收货地址编辑
            </div>
            <div class="x20"></div>
            <a-form-model :label-col="{ span: 3 }" :wrapper-col="{ span: 18 }">
                <a-form-model-item label="收货人">
                    <a-input v-model="info.receive_name"></a-input>
                </a-form-model-item>
                <a-form-model-item label="手机">
                    <a-input v-model="info.receive_tel"></a-input>
                </a-form-model-item>
                <a-form-model-item label="地区">
                    <a-cascader v-model="info.area_id" :field-names="{ label: 'name', value: 'id', children: 'children' }" :options="areas" placeholder="" @change="area_change" />
                </a-form-model-item>
                <a-form-model-item label="详细地址">
                    <a-input v-model="info.address"></a-input>
                </a-form-model-item>
                <a-form-model-item label="设置默认地址">
                    <a-switch :checked="info.is_default==1?true:false"  @change="onChange" />
                </a-form-model-item>
                <a-form-model-item :wrapper-col="{ span: 12, offset: 3 }">
                    <div class="submit_btn" @click="handleSubmit">确定提交</div>
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
              is_default:0,
              area_id:[],
          },
          areas:[],
          id:0,
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){

            // 验证代码处
            if(this.$isEmpty(this.info.receive_name)){
                return this.$message.error('收货人不能为空');
            }
            if(this.$isEmpty(this.info.receive_tel)){
                return this.$message.error('手机不能为空');
            }
            if(this.$isEmpty(this.info.area_id)){
                return this.$message.error('详细地址');
            }
            if(this.$isEmpty(this.info.address)){
                return this.$message.error('地区');
            }
        
            let api = this.$apiHandle(this.$api.homeAddress,this.id);
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
        area_change(row,info){
            this.info.province_id = row[0];
            this.info.city_id = row[1];
            this.info.region_id = row[2];
        },
        // 获取地址
        get_areas(){
            this.$get(this.$api.homeAreas).then(res=>{
                this.areas = res.data
            })
        },
        onChange(e){
            this.info.is_default = e?1:0;
        },
        get_info(){
            this.$get(this.$api.homeAddress+'/'+this.id).then(res=>{
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
        this.get_areas();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>