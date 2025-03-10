<template lang="">
    <div>
        <Pie v-if="report_store.details.report_charts.chart == 'pie'"/>
        <Vertical v-else-if="report_store.details.report_charts.chart == 'vertical'"/>
        <Horizontal v-else-if="report_store.details.report_charts.chart == 'horizontal'"/>
        <Line v-else-if="report_store.details.report_charts.chart == 'line'"/>
    </div>
</template>
<script>
import Line from "./line.vue";
import Horizontal from "./horizontal.vue";
import Pie from "./pie.vue";
import Vertical from "./vertical.vue";
import { useReportStore } from '@/stores/report';
const report_store = useReportStore();
export default {
    components:{
        Pie,Vertical,Horizontal,Line
    },
    data(){
        return{
            report_store
        }
    },
}
</script>
<style lang="">
    
</style>