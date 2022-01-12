<template>
    <div class="qwit">
        <table-view :options="options"></table-view>
    </div>
</template>

<script>
import {reactive,getCurrentInstance} from "vue"
import tableView from "@/components/common/table"
export default {
    components:{tableView},
    setup(props) {
        const options = reactive({

        });
        return {options}
    }
}
</script>

<style>

</style>