<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>店铺配置</div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="店铺名称" prop="store_name" :rules="[{required:true,message:'店铺名称不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.store_name"></el-input></el-form-item>
                    <el-form-item label="店铺描述" prop="store_description"><el-input type="textarea" placeholder="请输入内容" maxlength="200" show-word-limit v-model="info.store_description"></el-input></el-form-item>
                    <el-form-item label="店铺LOGO" prop="store_logo"><el-upload class="avatar-uploader" :action="$api.storeLogoUpload" :headers="upload_headers" :show-file-list="false" :on-success="handleAvatarSuccess" >
                        <img v-if="info.store_logo" :src="info.store_logo" class="avatar-upload">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload></el-form-item>
                    <el-form-item label="联系电话" prop="store_mobile"><el-input placeholder="请输入内容" v-model="info.store_mobile"></el-input></el-form-item>
                    <el-form-item label="公司地址" prop="area_info" :rules="[{required:true,message:'公司地址不能为空',trigger: 'blur' }]"><el-cascader v-model="info.area_info" :props="props" :options="area_list"></el-cascader></el-form-item>
                    <el-form-item label="详细地址" prop="store_address"><el-input placeholder="请输入内容"  v-model="info.store_address"></el-input></el-form-item>
                    <el-form-item label="地图定位" class="width_auto_60"><el-amap class="amap_demo" vid="amap" :plugin="amap_config.plugin" :center="amap_config.center" :events="amap_config.mapEvents">
                        <el-amap-marker vid="component-marker" :position="amap_config.position"></el-amap-marker>    
                    </el-amap></el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">保存</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    components: {},
    props: {},
    data() {
      let _this = this;
      return {
          info:{
              area_info:[],
              store_description:'',
          },
          area_list:[],
          // 省市区动态加载
          props:{
              value:'area_id',
              label:'name',
          },
          amap_config:{
              center:[116.397428, 39.90923],
              position:[116.397428, 39.90923],
              plugin:[
                    {pName: 'ToolBar'},
                    {pName:'Scale'},
              ],
              // 地图对象处理
              mapEvents:{
                  init(map) {
                      map.on('click',function(e){
                          let position = [];
                          position.push(e.lnglat.lng);
                          position.push(e.lnglat.lat);
                          _this.amap_config.position = position;
                          _this.info.lng = e.lnglat.lng;
                          _this.info.lat = e.lnglat.lat;
                      });
                  }
              },
          },
          upload_headers:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        submitForm:function(e){
            this.$refs[e].validate(res=>{
                if(res){
                    // Http 请求
                    this.$post(this.$api.storeInfoSetting,this.info).then(postRes=>{
                        if(postRes.code == 200){
                            this.$message.success("编辑成功");
                            this.get_store_info();
                        }else{
                            this.$message.error("编辑失败");
                        }
                    });
                }
            });
        },
        handleAvatarSuccess(res) {
            this.info.store_logo = res.data;
            this.$forceUpdate();
        },
        get_store_info(){
            this.$get(this.$api.storeInfoSetting).then(res=>{
                this.amap_config.center = [res.data.lng,res.data.lat];
                this.amap_config.position = [res.data.lng,res.data.lat];
                this.info  = res.data;
            });
        },
        get_area_list:function(){
            this.$get(this.$api.sellerGetAreaList).then(res=>{
                this.area_list = res.data;
            });
        },
        
    },
    created() {
        this.get_store_info();
        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到
        this.get_area_list();
    },
    mounted() {
    }
};
</script>
<style lang="scss" scoped>
.amap_demo {
  height: 400px;
}
</style>