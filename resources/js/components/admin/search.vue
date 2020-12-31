<template>
    <div class="admin_table_page_where" v-if="show">
        <a-form layout="inline">
            <a-row :gutter="48">
               
                <a-col :md="6" :sm="24" v-for="(v,k) in searchConfig1" :key="k">
                    <a-form-item :label="v.label">
                        <a-input v-if="v.type=='text'" v-model="params[v.name]" :placeholder="v.placeholder||''"/>
                        <a-select  v-if="v.type=='select'" v-model="params[v.name]" :placeholder="v.placeholder||''">
                            <a-select-option v-for="(vo,key) in v.data" :key="key" :value="vo.value">{{vo.label}}</a-select-option>
                        </a-select>
                        <a-time-picker v-if="v.type=='time_picker'" v-model="params[v.name]" />
                        <a-range-picker style="width:100%" v-if="v.type=='date_picker'" v-model="params[v.name]" format="YYYY-MM-DD HH:mm:ss" show-time :allow-clear="false"></a-range-picker>
                    </a-form-item>
                </a-col>
                
                <template v-if="advanced && searchConfig2.length>0">
                    <a-col :md="6" :sm="24" v-for="(v,k) in searchConfig2" :key="k">
                        <a-form-item :label="v.label">
                            <a-input v-if="v.type=='text'" v-model="params[v.name]" :placeholder="v.placeholder||''"/>
                            <a-select  v-if="v.type=='select'" v-model="params[v.name]" :placeholder="v.placeholder||''">
                                <a-select-option v-for="(vo,key) in v.data" :key="key" :value="vo.value">{{vo.label}}</a-select-option>
                            </a-select>
                            <a-time-picker v-if="v.type=='time_picker'" v-model="params[v.name]" />
                            <a-range-picker style="width:100%" v-if="v.type=='date_picker'" v-model="params[v.name]" format="YYYY-MM-DD HH:mm:ss" show-time :allow-clear="false"></a-range-picker>
                        </a-form-item>
                    </a-col>
                </template>
                <a-col :md="!advanced && 6 || 24" :sm="24" style="padding-top:3px;">
                    <span class="table-page-search-submitButtons" :style="advanced && { float: 'right', overflow: 'hidden' } || {} ">
                        <a-button icon="search" @click="search()">查询</a-button>
                        <a-button style="margin-left: 8px" @click="() => this.params = {}">重置</a-button>
                        <a v-if="searchConfig2.length>0" @click="advanced = !advanced" style="margin-left: 8px">
                        {{ advanced ? '收起' : '展开' }}
                        <a-icon :type="advanced ? 'up' : 'down'"/>
                        </a>
                    </span>
                </a-col>
            </a-row>
        </a-form>
    </div>
</template>

<script>
export default {
    props: {
        // 是否显示
        show:{
            type:Boolean,
            default:true,
        },
        // 配置
        searchConfig:{
            type:Array,
            default:[],
        },
        autoParams:{
            type:Object,
            default:{},
        }
    },
    data() {
      return {
          advanced:false,
          searchConfig1:[],
          searchConfig2:[],
          params:{
          },
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 点击搜索
        search(){
            this.$emit('searchParams',this.params);
        },
        timeFormat(times){
            return moment(times).format('YYYY-MM-DD HH:mm:ss');
        },

    },
    created() {
        if(this.searchConfig.length<=0){
            this.show = false;
        }else if(this.searchConfig.length<=3){
            this.searchConfig1 = this.searchConfig;
        }else if(this.searchConfig.length>3){
            this.searchConfig1 = this.searchConfig.slice(0,3);
            this.searchConfig2 = this.searchConfig.slice(3);
        }
        let autoParamsArr = Object.keys(this.autoParams);
        if(autoParamsArr.length>0){
            autoParamsArr.forEach(item=>{
                this.params[item] = this.autoParams[item];
            });
        }
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>