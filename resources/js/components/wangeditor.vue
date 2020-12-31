<template>
    <div class="wangeditor">
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
          toolbar:[
                'head',  // 标题
                'bold',  // 粗体
                'fontSize',  // 字号
                'fontName',  // 字体
                'italic',  // 斜体
                'underline',  // 下划线
                'strikeThrough',  // 删除线
                'foreColor',  // 文字颜色
                'backColor',  // 背景颜色
                'link',  // 插入链接
                'list',  // 列表
                'justify',  // 对齐方式
                'quote',  // 引用
                'emoticon',  // 表情
                'image',  // 插入图片
                'table',  // 表格
                'video',  // 插入视频
                // 'code',  // 插入代码
                // 'undo',  // 撤销
                // 'redo'  // 重复
          ],
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
            let token_type = sessionStorage.getItem('token_type');
            this.token = this.$getSession('token_type');
            var editor = new wangeditor('#editor');
            this.content = this.contents;
            editor.customConfig.debug = true;
            editor.customConfig.zIndex = 100;
            editor.customConfig.showLinkImg = false;
            editor.customConfig.uploadFileName = 'file[]';
            editor.customConfig.uploadImgMaxLength = 5;
            if(token_type=='admin_token'){
                editor.customConfig.uploadImgServer = this.$api.adminEditor;  // 上传图片到服务器
            }
            if(token_type=='seller_token'){
                editor.customConfig.uploadImgServer = this.$api.sellerEditor;  // 上传图片到服务器
            }
            
            editor.customConfig.uploadImgParams = {token:this.token};
            editor.customConfig.menus = this.toolbar;
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
.w-e-toolbar{
  flex-wrap:wrap;
}
</style>