<template>
    <span>
        <el-tag :style="styles" v-if="!data.isText && value != ''" :color="data.color" :type="data.type||''" :effect="effect" :size="size">{{value}}</el-tag>
        <span v-else>{{value||'-'}}</span>
    </span>
</template>

<script>
import {reactive,onMounted,watch} from "vue"
export default {
    props:{
        color:{
            type:String,
            default:null
        },
        effect:{
            type:String,
            default:null
        },
        size:{
            type:String,
            default:null
        },
        value:{
            default:''
        },
        tag_type:{
            default:false
        },
        styles:{
            type:Object,
            default:()=>{
                return {}
            }
        },
    },
    setup(props) {
        const data = reactive({color:null,type:'',isText:false})
        const COLORS = ['', 'success', 'warning', 'danger']
        const hashStr = ()=>{
            let str = props.value
            if (str == null) {
                return 0
            }
            if (typeof str !== 'string') {
                str = JSON.stringify(str)
            }
            let hash = 0; let i; let chr; let len
            if (str.length === 0) return hash
            for (i = 0, len = str.length; i < len; i++) {
                chr = str.charCodeAt(i)
                hash = ((hash << 5) - hash) + chr
                hash |= 0 // Convert to 32bit integer
            }
            return hash
        }

        if(props.color !== null){
            data.color = props.color
            data.type = null
        }else if(props.tag_type !== false){
            if(props.value == 1 || props.value == '1' || props.value== true || props.value == '成功' || props.value == 'Yes') data.type = 'success'
                if(props.value == 0 || props.value == '0' || props.value== false || props.value == '失败' || props.value == 'No') data.type = 'danger'
        }else{
            const hashcode = hashStr()
            data.type = COLORS[hashcode % COLORS.length]||''
        }
        
        const reloadVal = ()=>{
            if(props.value === '' || props.value === null || props.value === undefined){
                data.isText = true
            }
            if(props.color !== null){
                data.color = props.color
                data.type = null
            }else if(props.tag_type !== false){
                if(props.value == 1 || props.value == '1' || props.value== true || props.value == '成功' || props.value == 'Yes') data.type = 'success'
                if(props.value == 0 || props.value == '0' || props.value== false || props.value == '失败' || props.value == 'No') data.type = 'danger'
            }else{
                const hashcode = hashStr()
                data.type = COLORS[hashcode % COLORS.length]||''
            }

        }

        // const value = ref(props.value)
        watch(()=>props.value,(e)=>{
            reloadVal()
        })
        
        return {data,reloadVal}
    }
}
</script>

<style>

</style>