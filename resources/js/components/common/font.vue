<template>
    <div class="qw_font">
        <el-autocomplete  style="width:100%" :fetch-suggestions="querySearch" @select="handleSelect" v-model="value">
            <template #suffix>
                <el-icon class="el-input__icon">
                    <i :class="'fa '+value " />
                </el-icon>
            </template>
            <template #default="{ item }">
                <div class="value"> <i :class="'fa '+item " /> {{ item}}</div>
            </template>
        </el-autocomplete>
    </div>
</template>

<script>
import {watch} from "vue"
import fontjs from '@/plugins/font'
export default {
    props:['value'],
    setup(props,{emit}) {
        const querySearch = (queryString, cb)=>{
            let res = fontjs.filter((e)=>{
                return e.search(queryString)>-1
            })
            cb(res)
        }

        watch(()=>props.value,(e)=>{
            emit('update:value',e)
        })

        const handleSelect = (e)=>{
            emit('update:value',e)
        }

        return {handleSelect,querySearch}
    }
}
</script>

<style>

</style>