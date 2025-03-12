<template lang="">
    <div>
       <div class="grid md:grid-cols-6 sm:grid-cols-2 grid-cols-1 gap-3">
            <div
                v-for="(item, i) in dashboard_store.widgets"
                :key="i"
                >
                <SkeletonTable v-if="item.loading" :count="0"/>
                <div class="rounded-md p-4 shadow-md text-center bg-white dark:bg-slate-800" v-else>
                    <span
                    class="block text-sm text-slate-600 font-medium dark:text-white mb-1"
                    >{{ item.title }}</span
                    >
                    <span
                        class="block mb- text-2xl text-slate-900 dark:text-white font-medium"
                        >{{ item.count }}</span
                    >
                </div>
            </div>
        </div>
        <div class="md:flex gap-4 mt-4">
            <div class="w-full md:w-3/4">
                <div class="grid md:grid-cols-2 gap-4">
                    <SkeletonTable v-if="barchart_loading"/>
                    <Card title="# of Incidents, Trend over the 6 months" v-else>
                        <apexchart
                        ref="chart_vertical"
                        type="bar"
                        height="200px"
                        :options="BarChartConfiguration.chartOptions"
                        :series="BarChartConfiguration.series"
                        />
                    </Card>
                    <SkeletonTable v-if="piechart_loading"/>
                    <Card v-else title="Incident Status">
                        <apexchart
                        ref="chart_pie"
                        type="pie"
                        height="200px"
                        :options="PieChartConfiguration.chartOptions"
                        :series="PieChartConfiguration.series"
                        />
                    </Card>
                </div>
                <div class="grid mt-4">
                    <Card title="Incident Map">
                        <incidentMap :legend="false" classHeight='h-[500px]' />
                    </Card>  
                </div>
            </div>
            <div class="w-full md:w-1/4 mt-4 md:mt-0 ">
                <Card title="Activity logs">
                    <ul class="relative ltr:pl-2 rtl:pr-2 overflow-y-auto max-h-[700px] h-[700px]">
                        <li
                            v-for="(item, i) in dashboard_store.logs"
                            :key="i"
                            class="mt-5 flex justify-between items-center"
                        >
                            <div class="text-xs">
                                <b>{{item.whodid}}</b> {{item.action}}
                                <span v-if="item.status == 4 || item.status == 5 ">
                                    {{item.related_entityname}} 
                                    for
                                </span>
                                <span>
                                    {{item.entityname}}
                                </span>
                            </div>
                            <div class="text-xs">
                                {{item.timestamp}}
                            </div>
                        </li>
                    </ul>
                    <div class="mt-4">
                        <Button
                            v-if="!dashboard_store.logs_page.last"
                            @click="loadMore_SystemLogs"
                            text="Load more..."
                            btnClass="btn-outline-dark btn-sm w-full"
                            :isLoading="dashboard_store.loading"
                        />
                    </div>
                </Card>
            </div>
        </div>
    </div>
    </template>
    <script>
    import Button from "@/components/Button/index.vue";
    import SkeletonTable from "@/components/Skeleton/index.vue";
    import Card from "@/components/Card/index.vue";
    import { useDashboardStore } from "@/stores/dashboard";
    import { ref } from "vue";
    import incidentMap from "../Map/incident-map.vue";
    const barchart_loading = ref(false);
    const piechart_loading = ref(false);
    const dashboard_store = useDashboardStore();
    export default {
        components:{
            Card,
            Button,
            SkeletonTable,
            incidentMap,
            
        },
        mounted(){
            dashboard_store.widgets = [];
            dashboard_store.logs = [];
            dashboard_store.logs_page.last = false;
            dashboard_store.logs_page.current = 1;
            dashboard_store.get_widget("incidents",'Total Incidents',0)
            // dashboard_store.get_widget("incidents",'Active Incidents',1,'incident_statuses','<>','Closed:Cancelled')
            dashboard_store.get_widget("incidents",'Active Incidents',1,'incident_statuses','<>',4)
            dashboard_store.get_widget("incidents",'Closed Incidents',2,'incident_statuses','=',4)
            dashboard_store.system_logs();
            this.incident_trend_month();
            this.incident_status();
        },
        methods:{
            loadMore_SystemLogs(){
                dashboard_store.logs_page.current++;
                dashboard_store.system_logs();
            },
            async incident_trend_month(){
               barchart_loading.value = true;
               const response = await this.$axios.get("dashboard/chart/incident_trend_month");
               const data = response.data.data;
               setTimeout(()=>{
                        if(this.$refs.chart_vertical != null){
                            this.$refs.chart_vertical.updateOptions({
                                series:[{data:data.count}],
                                xaxis:{
                                    categories:data.label
                                }
                            });
                        }
                    },1000)
                barchart_loading.value = false;
            },
            async incident_status(){
                try {
                    piechart_loading.value = true;
                    const response = await this.$axios.get("dashboard/chart/incident_status");
                    const data = response.data.data;
                    setTimeout(()=>{
                        if(this.$refs.chart_pie != null){
                            this.$refs.chart_pie.updateOptions({
                                series:data.count,
                                labels:data.label
                            });
                        }
                    },1000)
                    piechart_loading.value = false;
                } catch (error) {
                    
                }
            }
        },
        computed:{
            BarChartConfiguration(){
                return{
                    loading:false,
                    series: [
                        {
                            data: [],
                        },
                    ],
                    chartOptions: {
                        chart: {
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                dataLabels: {
                                position: 'top',
                                },
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            offsetX: -6,
                            style: {
                                fontSize: '14px',
                                colors: ['#fff']
                            },
                        },
                        xaxis: {
                            categories: [],
                        },
                        colors: ["#4669FA"],
                    },
                }
            },
            PieChartConfiguration(){
                return{
                    series: [],
                    chartOptions: {
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
                    },
                }   
            }
        },
        data(){
            return{
                dashboard_store,
                barchart_loading,
                piechart_loading
            }
        }
    }
    </script>
    <style lang="">
        
    </style>