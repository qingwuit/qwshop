<template>
    <div class="search_layout">
        <el-row :gutter="20" v-if="options.length>0"  class="search_layout2">
            <el-col :sm="24" :md="8" :lg="4" v-for="(v,k) in advanced?optionsRef.slice(0,5):optionsRef" :key="k">
                <div class="search-content">
                    <q-input :params="v" :dictData="JSON.stringify(dictData) != '{}' && dictData[v.value]?dictData:(v.data||{})" v-model:formData="searchParams[v.value]" />
                    <!-- <el-input v-if="v.type='text'" :type="v.type" v-model="searchParams[v.value]" :placeholder="v.label" /> -->
                </div>
            </el-col>

            <!-- 搜索按钮 :icon="Search" -->
            <el-col :sm="24" :md="8" :lg="4">
                <div class="search-content">
                    <el-button type="primary"  @click="searchSubmit">{{$t('btn.search')}}</el-button>
                    <el-button @click="resetSearch">{{$t('btn.reset')}}</el-button>
                    <el-button type="text" v-if="optionsRef.length>5" @click="advanced=!advanced">{{advanced?$t('btn.open'):$t('btn.shrink')}} <i :class="advanced?'el-icon-arrow-down':'el-icon-arrow-up'" /> </el-button>
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>

import { Search} from '@element-plus/icons'
import {reactive,ref,getCurrentInstance} from "vue"
export default {
    components: {},
    props: {
        options:{
            type:Array,
            default:()=>{
                return []
            }
        },
        searchUrl:{
            type:String,
            default:""
        },
        dictData:{
            type:Object,
            default:()=>{
                return {}
            }
        }
    },
    setup(props,content){
        // options格式
        const optionsDefault = {
            type:'text', // 类型 number | select | select-v2 | password | cascader | date-picker | date-picker | date-time-picker 
            label:'Field', // 搜索字段名称 | 标题 | 名称
            value:'name', // 搜索字段名称 | name | title
            elabel:'Field', // 自定义英文字段名称 | name |title
            placeholder:'Field',
            where:null, // 自定义英文字段名称 | name |title
            dict:null, // 字典地址Url
            data:{}, // 列表数据或其他数据
        }

        // 接收的值
        let propOptions = props.options
        // if(propOptions.length<=0) return

        // 收缩状态
        let advanced = ref(false)
        if(propOptions.length > 3) advanced.value = true

        const {proxy} = getCurrentInstance()

        propOptions.forEach( async (item)=>{
            if(!item.type) item.type = optionsDefault.type
            if(!item.label) item.label = optionsDefault.label
            if(!item.value) item.value = optionsDefault.value
            if(item.elabel) item.label = item.elabel
            if(!item.data) item.data = optionsDefault.data
            if(item.dict) item.data[item.value] = await proxy.R.get(item.dict)
            if(!item.placeholder) item.placeholder = item.label
            if(!item.where) item.where = optionsDefault.where
        })

        const optionsRef = reactive(propOptions)
        const searchParams = reactive({})

        // 搜索按钮
        const searchSubmit = async ()=>{
            // let data = await proxy.R.get(props.searchUrl,{...searchParams})
            let newParams = _.cloneDeep(searchParams)
            // 其他非等于的条件处理
            propOptions.map(optionItem=>{
                if(newParams[optionItem.value] && optionItem.where !== null && optionItem.where !== undefined){
                    newParams[optionItem.value] += '|' + optionItem.where
                }
            })
            content.emit("data",newParams)
        }

        // 清空
        const resetSearch = ()=>{
            let resetData = {}
            optionsRef.map(item=>{
                resetData[item.value] = undefined
            })
            Object.assign(searchParams,resetData)
        }

        return {optionsRef,advanced,searchParams,searchSubmit,resetSearch,Search}
    }
    
};
</script>
<style lang="scss" scoped>
.search_layout2{
    border-bottom: 1px solid #efefef;
    margin-bottom: 15px;
}
.search_layout{
    .search-content{
        // background: #f1f1f1f1;
        height: 30px;
        margin-bottom: 20px;
    }
}
</style>