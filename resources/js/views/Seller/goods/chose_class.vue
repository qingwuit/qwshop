<template>
    <div class="qingwu">
        <div class="admin_table_page_title">
            <a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>
            栏目选择
        </div>
        <div class="unline underm"></div>
        <div class="admin_form">
            
            <div class="goods_add_chose_class_bg">
                <div class="chose_class_bg_item">
                    <ul><li @click="chose(v.id,0)" v-for="(v,k) in fList" :key="k" :class="choseId[0] == v.id?'checked':''">{{v.name}}<a-icon type="right" /></li></ul>
                </div>
                <div :class="choseId[0]==0?'chose_class_bg_item disabled':'chose_class_bg_item'">
                    <ul><li @click="chose(v.id,1)" v-for="(v,k) in sList" :key="k" :class="choseId[1] == v.id?'checked':''">{{v.name}}<a-icon type="right" /></li></ul>
                </div>
                <div :class="choseId[1]==0?'chose_class_bg_item disabled':'chose_class_bg_item'">
                    <ul><li @click="chose(v.id,2)" v-for="(v,k) in tList" :key="k" :class="choseId[2] == v.id?'checked':''">{{v.name}}<a-icon type="right" /></li></ul>
                </div>
            </div>

            <div class="chose_class_btn">
                <a-button type="primary" :disabled="choseId[0]==0 || choseId[1]==0 || choseId[2]==0" @click="next_step">下一步，填写商品信息</a-button>
            </div>
            
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          list:[],
          fList:[],
          sList:[],
          tList:[],
          choseId:[0,0,0],
      };
    },
    watch: {},
    computed: {},
    methods: {
        next_step(){
            
            let url = '/Seller/goods/form';
            if(!this.$isEmpty(this.$route.params.id)){
                url += '/'+this.$route.params.id;
            }
            this.$router.push(url+'?id='+this.choseId[0]+','+this.choseId[1]+','+this.choseId[2]);
        },
        onload(){
            this.$get(this.$api.sellerStoreGoodsClasses).then(res=>{
                this.list = res.data;
                this.list.forEach(item=>{
                    let f = false;
                    this.fList.forEach(fItem=>{
                        if(fItem.id == item[0].id){
                            f = true;
                        }
                    })

                    if(!f){
                        this.fList.push(item[0])
                    }
                })
            })
        },
        chose(id,deep){
            this.choseId[deep] = id;
            if(deep == 0){
                this.choseId[1] = 0;
                this.choseId[2] = 0;
                this.sList = [];
                this.tList = [];
                this.list.forEach(item=>{
                    if(item[0].id == this.choseId[0]){
                        let s = false;
                        this.sList.forEach(sItem=>{
                            if(sItem.id == item[1].id){
                                s = true;
                            }
                        })
                        
                        if(!s){
                            this.sList.push(item[1])
                        }
                    }
                    
                })
            }
            if(deep == 1){
                this.choseId[2] = 0;
                this.tList = [];
                this.list.forEach(item=>{
                    if(item[0].id == this.choseId[0] && item[1].id == this.choseId[1]){
                        let t = false;
                        this.tList.forEach(tItem=>{
                        if(tItem.id == item[2].id){
                                t = true;
                            }
                        })
                        if(!t){
                            this.tList.push(item[2])
                        }
                    }
                })
            }
            this.$forceUpdate();
        }
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.chose_class_btn{
    margin:40px 0;
    display: block;
    text-align: center;
}
.chose_class_bg_item{
    width: 30%;
    background: #fff;
    box-sizing: border-box;
    padding:10px;
    border: 1px solid #efefef;
    border-radius: 4px;
    float: left;
    margin-right: 5%;
    height: 398px;
    &:last-child{
        margin-right: 0;
    }
    &.disabled{
        background: #fafafa;
    }
    ul li{
        cursor: pointer;
        padding:4px 10px;
        margin-bottom: 10px;
        box-sizing: border-box;
        border: 1px solid #fff;
        i{
            float:right;
            line-height: 24px;
        }
        &.checked{
            border: 1px solid #d9ecff;
            background: #ecf5ff;
            color: #409eff;
            border-radius: 3px;
        }
        &:hover{
            border: 1px solid #d9ecff;
            background: #ecf5ff;
            color: #409eff;
            border-radius: 3px;
        }
    }
}

.goods_add_chose_class_bg{
    background: #fafafa;
    padding: 40px;
    width: 1000px;
    margin: 0 auto;
    border: 1px solid #eee;
    border-radius: 5px;
    &:after{
        content:'';
        clear:both;
        display: block;
    }
}

</style>