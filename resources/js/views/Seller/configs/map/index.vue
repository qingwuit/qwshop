<template>
    <a-form-model :label-col="{ span: 4 }" :wrapper-col="{ span: 12 }">
        
        <a-form-model-item label="地址">
            <a-cascader v-model="info.area_id" :field-names="{ label: 'name', value: 'id', children: 'children' }" :options="areas" placeholder="" @change="area_change" />
        </a-form-model-item>
        <a-form-model-item label="地图选址">
            <el-amap  class="amap-demo" vid="amapDemo" :plugin="plugin" :zoom="zoom" :events="mapEvents" :center="center">
                <el-amap-marker vid="component-marker" :position="[info.store_lng,info.store_lat]"></el-amap-marker>   
            </el-amap>
        </a-form-model-item>
        
        <a-form-model-item label="详细地址">
            <a-input v-model="info.store_address"></a-input>
        </a-form-model-item>
        
        <a-form-model-item :wrapper-col="{ span: 12, offset: 4 }">
            <a-button type="primary" @click="handleSubmit">提交</a-button>
        </a-form-model-item>
    </a-form-model>
</template>

<script>

window.VueAMap.initAMapApiLoader({
  // 高德key
  key: 'f7619d49a4aea5cb76631ce884ea1817',
  // 插件集合 （插件按需引入）
  plugin: ['AMap.Geolocation', 'AMap.ToolBar','AMap.Geocoder']
});
export default {
    components: {},
    props: {},
    data() {
      let _this = this;
      return {
            info:{
                store_lng:'116.46',
                store_lat:'39.92',
            },
            areas:[],
            center:[116.46, 39.92], // 默认中心
            zoom: 12, // 范围大小
            plugin: [
                {pName: 'ToolBar',},
            ],
            // 地图对象处理
            mapEvents:{
                init(map) {
                    map.on('click',function(e){
                        let position = [];
                        position.push(e.lnglat.lng);
                        position.push(e.lnglat.lat);
                        _this.center = position;
                        _this.info.store_lng = e.lnglat.lng;
                        _this.info.store_lat = e.lnglat.lat;
                    });
                }
            },
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){
            this.info.edit_type = 'map';
            this.$put(this.$api.sellerConfigs,this.info).then(res=>{
                this.get_info();
                return this.$returnInfo(res);
            })
        },
        get_info(){
            this.$get(this.$api.sellerConfigs).then(res=>{
                this.center = [res.data.store_lng,res.data.store_lat];
                this.info = res.data;
            })
        },
        // 获取地址
        get_areas(){
            this.$get(this.$api.sellerAreas).then(res=>{
                this.areas = res.data
            })
        },
        area_change(row,info){
            this.info.province_id = row[0];
            this.info.city_id = row[1];
            this.info.region_id = row[2];
            this.info.area_info = info[0].name+' '+info[1].name+' '+info[2].name

            // 修改地址
            window.VueAMap.lazyAMapApiLoaderInstance.load().then(() => {
               
                AMap.plugin('AMap.Geocoder',  ()=> {
                    var districtSearch = new AMap.Geocoder();
                    // console.log(this.info.area_info)
                    // 搜索所有省/直辖市信息
                    districtSearch.getLocation(info[2].name, (status, result)=> {
                        // 查询成功时，result即为对应的行政区信息
                        if (status === 'complete' && result.info === 'OK') {
                            this.center = [result.geocodes[0].location.lng,result.geocodes[0].location.lat]
                        }else{
                            console.log(status, result)
                        }
                        
                    })
                })
            });
        },
    },
    created() {
        this.get_info();
        this.get_areas();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.amap-demo {
    height: 300px;
}
</style>