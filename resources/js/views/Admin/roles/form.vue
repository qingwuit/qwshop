<template>
    <div class="qingwu">
        <div class="admin_table_page_title"><a-button @click="$router.back()" class="float_right" icon="arrow-left">返回</a-button>角色编辑</div>
        <div class="unline underm"></div>
        <div class="admin_form">
            <a-form-model :label-col="{ span: 3 }" :wrapper-col="{ span: 18 }">
                <a-form-model-item label="角色名称">
                    <a-input v-model="info.name"></a-input>
                </a-form-model-item>
                <a-form-model-item label="菜单权限">
                    <a-tree-select :replace-fields="{children:'children', title:'name', key:'id', value: 'id' }" v-model="info.menu_id" :tree-data="list" tree-checkable :show-checked-strategy="SHOW_PARENT"></a-tree-select>
                    <!-- <a-tree-select v-model="info.menu_id" >
                        <a-tree-select-node v-for="(v,k) in list" :key="k" :value="v.id" :title="v.name"></a-tree-select-node>
                    </a-tree-select> -->
               
                </a-form-model-item>
                <a-form-model-item label="接口权限">
                    <div class="check_list">
                        <div class="item" v-for="(v,k) in permissions" :key="k">
                            <div class="permission_title">{{v.name}}<a-tag @click="check_all(k)" style="float:right;margin-top:10px;">点击全选</a-tag></div>
                            <div class="cblock">
                                <div class="cbox" v-for="(vo,key) in v.permissions" :key="key">
                                    <a-checkbox v-model="vo.checked">{{vo.name}}</a-checkbox><a-tag color="red">{{vo.apis}}</a-tag>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
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
            //   menu_id:[],
              permission_id:[],
          },
          id:0,
          list:[],
          permissions:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSubmit(){

            // 验证代码处
            if(this.$isEmpty(this.info.name)){
                return this.$message.error('角色名称不能为空');
            }
            this.get_check_permission(); // 获取选择的接口
        
            let api = this.$apiHandle(this.$api.adminRoles,this.id);
            if(api.status){
                this.info.menus = undefined;
                this.info.permissions = undefined;
                this.$put(api.url,this.info).then(res=>{
                    if(res.code == 200){
                        this.$message.success(res.msg)
                        return this.$router.back();
                    }else{
                        return this.$message.error(res.msg)
                    }
                })
            }else{
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
            this.$get(this.$api.adminRoles+'/'+this.id).then(res=>{
                res.data.menu_id = [];
                res.data.permission_id = [];
                res.data.menus.forEach(item=>{
                    res.data.menu_id.push(item.id);
                })
                res.data.permissions.forEach(item=>{
                    res.data.permission_id.push(item.id);
                })
                this.info = res.data;

                this.$get(this.$api.adminPermissionGroups,{per_page:100}).then(res=>{
                    this.permissions = res.data.data;
                    this.permissions.forEach(items=>{
                        items.permissions.forEach(item=>{
                            if(this.info.permission_id.indexOf(item.id)>-1){
                                this.$set(item,'checked',true)
                            }else{
                                this.$set(item,'checked',false)
                            }
                        })
                    })
                });
                
            })
        },
        // 获取菜单列表
        onload(){
            // 判断你是否是编辑
            if(!this.$isEmpty(this.$route.params.id)){
                this.id = this.$route.params.id;
                this.get_info();
            }else{
                this.$get(this.$api.adminPermissionGroups,{per_page:100}).then(res=>{
                    this.permissions = res.data.data;
                });
            }

            this.$get(this.$api.adminMenus).then(res=>{
                this.list = res.data;
            });
 
        },
        check_all(e){
            this.permissions[e].permissions.forEach(item=>{
                if(this.$isEmpty(item.checked)){
                    this.$set(item,'checked',true)
                }else{
                    item.checked = !item.checked;
                }
            })
        },
        get_check_permission(){
            let permission_id = [];
            this.permissions.forEach(items=>{
                items.permissions.forEach(item=>{
                    if(!this.$isEmpty(item.checked) && item.checked){
                        permission_id.push(item.id)
                    }
                })
            })
            if(permission_id.length>0){
                this.info.permission_id = permission_id;
            }else{
                this.info.permission_id = undefined;
            }
        },
        
    },
    created() {
        this.onload();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.permission_title{
    background: #efefef;
    padding:0 20px;
    box-sizing: border-box;
    border-radius: 4px;
}
.cblock{
    margin-top: 10px;
    margin-bottom: 10px;
}
.cbox{
    width: 33%;
    float: left;
}
</style>