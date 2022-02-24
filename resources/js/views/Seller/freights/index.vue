<template>
    <div class="qwcfg">
        <el-form  label-position="right" label-width="140px">
            <el-form-item :label="$t('freight.default')" >
                <el-row :gutter="5">
                    <el-col :md="6" :sm="24" ><el-input type="number" v-model="data.list[0]['f_weight']" >
                        <template #prepend>首重</template>
                        <template #append>kg内</template>
                    </el-input></el-col>
                    <el-col :md="6" :sm="24" ><el-input type="number"  v-model="data.list[0]['f_price']" >
                        <template #prepend>运费</template>
                        <template #append>{{$t('btn.money')}}</template>
                    </el-input></el-col>
                    <el-col :md="6" :sm="24" ><el-input type="number"  v-model="data.list[0]['o_weight']"  >
                        <template #prepend>每增加</template>
                        <template #append>kg</template>
                    </el-input></el-col>
                    <el-col :md="6" :sm="24" ><el-input type="number"  v-model="data.list[0]['o_price']"  >
                        <template #prepend>增加运费</template>
                        <template #append>{{$t('btn.money')}}</template>
                    </el-input></el-col>
                </el-row>
            </el-form-item>
            <el-form-item :label="$t('freight.custom')" >
                <el-row :gutter="5" style="background:#efefef;text-align:center;width:90%">
                    <el-col :md="4" :sm="24" >标题</el-col>
                    <el-col :md="4" :sm="24" >首重（kg）</el-col>
                    <el-col :md="4" :sm="24" >运费（{{$t('btn.money')}}）</el-col>
                    <el-col :md="4" :sm="24" >续重（kg）</el-col>
                    <el-col :md="4" :sm="24" >续费（{{$t('btn.money')}}）</el-col>
                    <el-col :md="4" :sm="24" >操作</el-col>
                </el-row>
                <div v-for="(v,k) in data.list" :key="k">
                    <div v-if="k>0">
                        <el-row :gutter="8" style="padding:10px">
                            <el-col :md="4" :sm="24" ><el-input v-model="data.list[k].name" /></el-col>
                            <el-col :md="4" :sm="24" ><el-input type="number" v-model="v.f_weight">
                                <template #append>kg</template>
                            </el-input></el-col>
                            <el-col :md="4" :sm="24" ><el-input type="number" v-model="data.list[k].f_price">
                                <template #append>{{$t('btn.money')}}</template>
                            </el-input></el-col>
                            <el-col :md="4" :sm="24" ><el-input type="number" v-model="data.list[k].o_weight">
                                <template #append>kg</template>
                            </el-input></el-col>
                            <el-col :md="4" :sm="24" ><el-input type="number" v-model="data.list[k].o_price">
                                <template #append>{{$t('btn.money')}}</template>
                            </el-input></el-col>
                            <el-col :md="4" :sm="24" style="text-align:center;"><el-button @click="showArea(k)" size="small">区域</el-button><el-button style="margin-left:10px" type="danger" size="small" @click="del(v.id,k)">删除</el-button></el-col>
                        </el-row>
                        <div class="area_list" v-show="data.list[k].area_show">
                            <el-checkbox-group  v-model="data.list[k].area_id" @change="onChange">
                                <el-checkbox v-for="(vo,key) in data.areas" :key="key" :label="vo.id+''">{{vo.name}}</el-checkbox>
                            </el-checkbox-group>
                        </div>
                    </div>
                </div>
            </el-form-item>
            <el-form-item >
                <el-button type="primary" :loading="loading" @click="onSubmit()">{{$t('btn.determine')}}</el-button>
                <el-button @click="add" :icon="Edit">{{$t('btn.add')}}</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import {reactive,ref,getCurrentInstance} from "vue"
import { Edit } from '@element-plus/icons'
export default {
    components:{},
    setup() {
        const {proxy} = getCurrentInstance()
        const loading = ref(false)

        // 获取数据列表
        const data = reactive({
            list:[{id:0,name:'自定义运费模版',f_weight:0.00,f_price:0.00,o_weight:0,o_price:0.00,area_id:[],area_show:false}],
            areas:[],
        })
        

        const loadData = async ()=>{
            loadArea()
            const resp = await proxy.R.get('/Seller/freights',{isAll:true})
            if(!resp.code && resp.length>0) data.list = resp
        }

        const loadArea = async ()=>{
            data.areas = await proxy.R.get('/load_areas?deep=1')
        }

        const del = async (id,k)=>{
            data.list.splice(k,1)
            if(id > 0) await proxy.R.deletes('/Seller/freights/'+id)
            loadData()

        }

        const showArea = (k)=>{
            data.list[k].area_show = !data.list[k].area_show
        }

        const onChange = (e)=>{
            console.log(e)
        }

        const onSubmit = async ()=>{
            loading.value = true
            proxy.R.put('/Seller/freights/0',{info:data.list}).then(res=>{
            }).finally(()=>{
                loadData()
                loading.value = false
            })
        }

        const add = ()=>{
            let obj = {id:0,name:'自定义运费模版',f_weight:0.00,f_price:0.00,o_weight:0,o_price:0.00,area_id:[],area_show:false};
            data.list.push(obj);
        }

        loadData()

        return {Edit,loading,data,del,onChange,onSubmit,showArea,add}
    }
}
</script>