<template>
    <div class="qwit">
        <table-view :options="options"></table-view>
    </div>
</template>

<script setup>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"

const {proxy} = getCurrentInstance()

const options = reactive({

});
</script>

<style>

</style>