<template>
    <a-config-provider :locale="locale">
        <router-view></router-view>
    </a-config-provider>
</template>

<script>
const { locales } = window.antd;
export default {
    components: {},
    props: {},
    data() {
      return {
          locale: locales.zh_CN,
      };
    },
    watch: {},
    computed: {},
    methods: {},
    created() {console.log('青梧系统： https//:www.qingwuit.com  QQ群：1062159788');},
    mounted() {},
    
};
</script>
<style lang="scss">
#app{
  color:#333;
  font-size: 14px;
  height: 100%;
}
</style>