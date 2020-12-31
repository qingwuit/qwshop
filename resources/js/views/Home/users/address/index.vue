<template>
    <div class="user_address">
        <div class="user_main">
            <div class="block_title">
                <span><div class="btn" @click="$router.push('/user/address/form')">新增地址</div></span>
                收货地址
            </div>
            <div class="x20"></div>
            <div class="address_list" v-if="addresses.length>0">
                <ul>
                    <li v-for="(v,k) in addresses" :key="k">
                        <div class="pos_img"><img :src="v.is_default==1?require('@/asset/order/address_pos2.png'):require('@/asset/order/address_pos.png')" alt=""></div>
                        <div class="name">{{v.receive_name}}</div>
                        <div class="area_info">{{v.area_info+' '+v.address}}  <font style="font-weight:bold;margin-left:10px">({{v.receive_tel}})</font></div>
                        <div class="handle"><span v-if="v.is_default==0" @click="set_default(v.id)">设置默认</span><font v-if="v.is_default==0">|</font><span @click="$router.push('/user/address/form/'+v.id)">编辑</span>|<span @click="del(v.id)">删除</span></div>
                    </li>
                </ul>
            </div>

            <a-empty v-else />
        </div>

    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          visible:false,
          addresses:[],
          info:{}
      };
    },
    watch: {},
    computed: {},
    methods: {
        get_addresses(){
            this.$get(this.$api.homeAddress).then(res=>{
                this.addresses = res.data;
            })
        },
        add_address(){
            this.$post(this.$api.homeAddress,this.info).then(res=>{
                this.$returnInfo(res);
            })
        },
        del(id){
            this.$delete(this.$api.homeAddress+'/'+id).then(res=>{
                if(res.code == 200){
                    this.get_addresses();
                    this.$message.success('删除成功');
                }else{
                    this.$message.error(res.msg)
                }
            });
        },
        set_default(id){
            this.$put(this.$api.homeAddress+'/default/set',{id:id}).then(res=>{
                if(res.code == 200){
                    this.get_addresses();
                    this.$message.success('设置成功');
                }else{
                    this.$message.error(res.msg)
                }
            })
        }
    },
    created() {
        this.get_addresses();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.address_list{
    ul li{
        border-bottom: 1px solid #efefef;
        padding:20px 0;
        position: relative;
        padding-left: 42px;
        &:first-child{
            padding-top: 0;
            .pos_img{
                left: 0;
                top:8px;
            }
            .handle{
                top:10px;
            }
        }
        .name{
            font-size: 16px;
            font-weight: bold;
        }
        .pos_img{
            position: absolute;
            left: 0;
            top:26px;
        }
        .handle{
            position: absolute;
            right: 0;
            top:28px;
            cursor: pointer;
            span{
                margin:5px;
                &:hover{
                    color:#ca151e;
                }
            }
        }
    }
}
</style>