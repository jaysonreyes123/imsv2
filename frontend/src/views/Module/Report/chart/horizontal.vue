<template lang="">
    <div>
        <br>
        <VueApexCharts
            type="bar"
            ref="chart"
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
const chart_option =ref( 
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
    plotOptions: {
        bar: {
            horizontal: true,
        },
    },
    dataLabels: {
        enabled: true,
    },
    xaxis: {
        categories: [],
    },
});
export default {
    components:{
        VueApexCharts,Card
    },
    data(){
        return{
            report_store,chart_option,series
        }
    },
    mounted(){
        setTimeout(()=>{
            const data = report_store.data.filter(item=> item.label != null);
            const series_data = data.map(item=>item.count);
            const label = data.map(item => item.label);

            this.$refs.chart.updateSeries([{
                data: series_data,
                name:"count"
            }])
            this.$refs.chart.updateOptions({
                xaxis:{
                    categories:label
                }
            })
        },1000)
    },
}
</script>
<style lang="">
    
</style>