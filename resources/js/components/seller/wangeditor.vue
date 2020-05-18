<template>
    <div class="qingwu">
        <div id="editor">

        </div>
        <textarea v-model="content" style="display:none;"></textarea>
    </div>
</template>

<script>
import wangeditor from 'wangeditor';
export default {
    components: {},
    props: {
        contents:{
            type:String,
            default:'',
        }
    },
    data() {
      return {
          token:'',
          content:'',
      };
    },
    watch: {
        contents:function(val){
            this.content = this.contents;
            this.create_editor();
        },
        
    },
    computed: {},
    methods: {
        create_editor:function(){
            var _this = this;
            this.token = localStorage.getItem('token');
            var editor = new wangeditor('#editor');
            this.content = this.contents;
            editor.customConfig.zIndex = 100;
            editor.customConfig.showLinkImg = false;
            editor.customConfig.uploadFileName = 'file[]';
            editor.customConfig.uploadImgMaxLength = 5;
            editor.customConfig.uploadImgServer = this.$api.autoUpload;  // 上传图片到服务器
            editor.customConfig.uploadImgParams = {token:this.token};
            editor.customConfig.onchange = function (html) {
                _this.content = html;
                _this.$emit("goods_content",_this.content);
            }
            editor.create();
            editor.txt.html(this.content);
        }
    },
    created() {
        
    },
    mounted() {
        this.create_editor();
    }
};
</script>
<style lang="scss" scoped>

</style>