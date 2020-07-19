<template>
    <div class="goods_attr_modal">
        <a-modal v-model="attrVisible" title="选择规格属性" @ok="handleOk" @cancel="cancel">
            <div class="attr_modal">
                <a-checkable-tag v-for="(v,k) in list" :key="k"
                    :checked="selectedTags.indexOf(v.id) > -1"
                    @change="checked => handleChange(v.id, checked)"
                >
                    {{ v.name }}
                </a-checkable-tag>
            </div>
        </a-modal>
    </div>
</template>

<script>
export default {
    components: {},
    props: {
        attrVisible:{
            type:Boolean,
            default:false,
        }
    },
    data() {
      return {
          list:[],
          selectedTags:[],
      };
    },
    watch: {},
    computed: {},
    methods: {
        cancel(){
            this.$emit('goods_attr_modal_cancel')
        },
        // 获取规格属性
        get_attr(){
            this.$get(this.$api.sellerGoodsAttrs,{per_page:100}).then(res=>{
                this.list = res.data.data
            })
        },
        handleChange(tag, checked){
            const { selectedTags } = this;
            const nextSelectedTags = checked
                ? [...selectedTags, tag]
                : selectedTags.filter(t => t !== tag);
            this.selectedTags = nextSelectedTags;
        },
        handleOk(){
            let attr = [];
            this.selectedTags.forEach(item=>{
                this.list.forEach(item2=>{
                    if(item == item2.id){
                        attr.push(item2)
                    }
                })
            })
            attr.forEach(items=>{
                items.specs.forEach(specItem=>{
                    specItem.check=false
                })
            })
            this.$emit('goods_attr',attr);
        }
    },
    created() {
        this.get_attr();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>