<template>
    <div class="user_user_info">
        <div class="user_main">
            <div class="block_title">
                订单评论
            </div>
            <div class="x20"></div>
            <div class="uif_block" >
                <a-form-model :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                    <a-form-model-item label="评论内容">
                         <a-textarea placeholder="输入评论内容..." :rows="4" allow-clear v-model="params.content" />
                    </a-form-model-item>
                    <a-form-model-item label='综合评分'>
                        <a-rate style="font-size:14px;line-height: 16px;" v-model="params.score" :tooltips="desc" />
                        <span class="ant-rate-text">{{ desc[params.score-1] }}分</span>
                    </a-form-model-item>
                    <a-form-model-item label='描述相符'>
                        <a-rate style="font-size:14px;line-height: 16px;" v-model="params.agree" :tooltips="desc" />
                        <span class="ant-rate-text">{{ desc[params.agree-1] }}分</span>
                    </a-form-model-item>
                    <a-form-model-item label='服务态度'>
                        <a-rate style="font-size:14px;line-height: 16px;" v-model="params.service" :tooltips="desc" />
                        <span class="ant-rate-text">{{ desc[params.service-1] }}分</span>
                    </a-form-model-item>
                    <a-form-model-item label='发货速度'>
                        <a-rate style="font-size:14px;line-height: 16px;" v-model="params.speed" :tooltips="desc" />
                        <span class="ant-rate-text">{{ desc[params.speed-1] }}分</span>
                    </a-form-model-item>
                    
                    <a-form-model-item label="图片上传(非必须)">
                        <div class="goods_image"  v-if="params.image.length>0">
                            <div class="item" v-if="params.image.length>0">
                                <div class="item_img" v-for="(v,k) in params.image" :key="k" >
                                    <img :src="v" />
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="item noimg" v-else><a-font type="iconphoto" /></div>
                        </div>
                        <a-upload
                            list-type="picture-card"
                            class="avatar-uploader"
                            :show-upload-list="false"
                            :multiple="true"
                            :action="$api.homeOrderComments+'/thumb/upload'"
                            :data="{token:$getSession('token_type')}"
                            @change="upload"
                        >
                            <div>
                                <a-font type='iconplus' />
                            </div>
                        </a-upload>
                    </a-form-model-item>
                    
                    <a-form-model-item :wrapper-col="{ span: 12, offset: 5 }">
                        <div class="submit_btn" @click="handleSubmit">确定提交</div>
                    </a-form-model-item>
                </a-form-model>
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
          info:{
              
          },
          params:{
              order_id:0,
              score:5,
              agree:5,
              service:5,
              speed:5,
              content:'',
              image:[],
          },
          desc: [1.00, 2.00, 3.00, 4.00, 5.00],
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){
            this.$post(this.$api.homeOrderComments,this.params).then(res=>{
                this.$returnInfo(res);
                return this.$router.go(-1);
            })
        },
        upload(e){
            if(this.params.image.length>5){
                return this.$message.error('图片不能超过5张');
            }
            if(e.file.status == 'done'){
                let rs = e.file.response;
                if(rs.code == 200){
                    this.params.image.push(rs.data) ;
                    console.log(this.params)
                }else{
                    return this.$message.error(rs.msg);
                }
            }
        }
      
    },
    created() {
        let id = this.$route.params.id;
        if(this.$isEmpty(id)){
            this.$messasge.error('error');
            return this.$router.go(-1);
        }else{
            this.params.order_id = id;
        }
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.goods_image{
    margin-bottom: 20px;
    .item{
        &.noimg{
            width:103px;
            height: 103px;
            background: #efefef;
            text-align: center;
            border-radius: 4px;
            i{
                font-size: 40px;
                line-height: 103px;
                color:#999;
            }
        }
        .item_img{
            width: 103px;
            height: 103px;
            display: block;
            float: left;
            box-sizing: border-box;
            margin-right: 10px;
            margin-bottom: 10px;
            position: relative;
            border:1px solid #efefef;
            img{
                width: 100%;
                height: 100%;
                border-radius: 4px;
            }
        }
    }
}
</style>