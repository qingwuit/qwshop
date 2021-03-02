<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            属性规格编辑
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-form-model-item label="属性名称" :rules="{ required: true}">
                    <a-input v-model="info.name" placeholder="color"></a-input>
                </a-form-model-item>
                <a-form-model-item label="规格">
                    <a-tag v-for="(v,k) in specs" :key="k" :closable="true" @close="handleClose(v)">{{v}}</a-tag>
                    <a-input
                        v-if="inputVisible"
                        ref="input"
                        type="text"
                        size="small"
                        :style="{ width: '78px' }"
                        :value="inputValue"
                        @change="handleInputChange"
                        @blur="handleInputConfirm"
                        @keyup.enter="handleInputConfirm"
                    />
                    <a-tag v-else style="background: #fff; borderStyle: dashed;" @click="showInput"><a-icon type="plus" /> Add Specs</a-tag>
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
          },
          id:0,
          inputVisible:false,
          inputValue: '',
          specs:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){

            // 验证代码处
            if(this.$isEmpty(this.info.name)){
                return this.$message.error('属性名不能为空');
            }

            
            let api = this.$apiHandle(this.$api.sellerGoodsAttrs,this.id);
            if(api.status){
                this.info.spec_name = this.specs;
                this.$put(api.url,this.info).then(res=>{
                    if(res.code == 200){
                        this.$message.success(res.msg)
                        return this.$router.back();
                    }else{
                        return this.$message.error(res.msg)
                    }
                })
            }else{
                this.info.spec_name = this.specs;
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
            this.$get(this.$api.sellerGoodsAttrs+'/'+this.id).then(res=>{
                this.specs = [];
                res.data.specs.forEach(item=>{
                    this.specs.push(item.name); 
                })
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
        showInput() {
            this.inputVisible = true;
            this.$nextTick(function() {
                this.$refs.input.focus();
            });
        },
        // 输入框修改
        handleInputChange(e) {
            this.inputValue = e.target.value;
        },
        // 确定添加规格
        handleInputConfirm() {
            const inputValue = this.inputValue;
            let tags = this.specs;
            if (inputValue && tags.indexOf(inputValue) === -1) {
                tags = [...tags, inputValue];
            }
            console.log(tags);
            Object.assign(this, {
                specs:tags,
                inputVisible: false,
                inputValue: '',
            });
        },
        // 删除规格
        handleClose(removedTag) {
            const tags = this.specs.filter(tag => tag !== removedTag);
            this.specs = tags;
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