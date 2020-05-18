<template>
    <div id="apps">
        <!-- <h1>Vue Router Demo App</h1> -->
        <router-view></router-view>
        <!-- <HelloWorld msg="Welcome to Your Vue.js App" /> -->
    </div>
</template>

<script>
// import HelloWorld from "./components/HelloWorld.vue";

export default {
    name: "app",
    components: {
        // HelloWorld
    },
    prop:{}
};
</script>

<style>
html,body,#app,#apps {
    height: 100%;
    margin:0;
    font-family: "Avenir", Helvetica, Arial, sans-serif;
}
ol,ul {list-style:none} 
a{text-decoration: none;color:#333;}
.qingwu{height: 100%;}
</style>
