<template lang="">
    <div>
        <vue-good-table 
            class="mt-4"
            styleClass="vgt-table"
            :columns="report_store.column"
            :rows="report_store.data"
        />
    </div>
</template>
<script>
import { useReportStore } from "@/stores/report";
const report_store = useReportStore();
export default {
    data(){
        return{
            report_store
        }
    },

}
</script>
<style>
    .vgt-table th span{
        @apply !text-sm;
    }
</style>