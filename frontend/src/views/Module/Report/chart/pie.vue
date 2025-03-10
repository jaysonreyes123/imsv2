<template lang="">
    <div>
        <VueApexCharts
            ref="chart"
            type="pie"
            height="600px"
            :options="chart_option"
            :series="series"
        />
    </div>
</template>
<script>
import Card from "@/components/Card/index.vue";
import VueApexCharts from 'vue3-apexcharts'
import { useReportStore } from '@/stores/report';
import { ref } from "vue";
const report_store = useReportStore();
const series = ref([]);
const chart_option = ref(
{
    chart: {
        toolbar: {
            show: true,
            tools:{
                selection: false,
                zoom: false,
                zoomin: false,
                zoomout: false,
                pan:false,
                reset:false
            }
        },
    },
    labels: [],
    dataLabels: {
    formatter: function (val, opts) {
        return opts.w.config.series[opts.seriesIndex]
    },
    enabled: true,
    },
    responsive: [
        {
            breakpoint: 480,
            options: {
            legend: {
                position: "bottom",
            },
            },
        },
    ],
}
)
export default {
    components:{
        VueApexCharts,Card
    },
    data(){
        return{
            report_store,series,chart_option
        }
    },
    mounted(){
        setTimeout(()=>{

            const data = report_store.data.filter(item => item.label != null);
            const series_data = data.map(item=>item.count);
            const label = data.map(item => item.label);

            this.$refs.chart.updateOptions({
                series:series_data,
                labels:label
            });
        },1000)
    },
}
</script>
<style lang="">
    
</style>