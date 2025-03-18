<template lang="">
    <Loading v-model:active="insight_report_store.loading"/>
    <div class="container">
        <div class="flex justify-center">
            <p class="title font-bold text-center text-2xl w-[400px]">Incident Management System {{insight_report_store.data.type}} Incident Summary Report</p>
        </div>
        <div class="mt-12">
            <p v-if="insight_report_store.data.type == 'Daily'"><span class="font-bold text-slate-800">Date: </span><i>{{insight_report_store.data.start_date}}</i></p>
            <p v-else><span class="font-bold text-slate-800">Date Range: </span><i>{{insight_report_store.data.start_date}} - {{insight_report_store.data.end_date}}</i></p>
            <p><span class="font-bold text-slate-800">Operational Period: </span><i>{{insight_report_store.data.start_time}} - {{insight_report_store.data.end_time}}</i></p>
        </div>
        <div class="w-full h-[1px] bg-slate-500 mt-6 mb-4"></div>
        <div>
            <p class="title font-bold !text-xl">Incident Summary</p>
            <br>
            <div>
                <div>
                    <div class="table-title">Incident Overview</div>
                    <vue-good-table 
                        :columns="incident_overview_column" 
                        :rows="incident_overview_data"
                        styleClass="vgt-table bordered"
                        :sort-options="{
                            enabled: false,
                        }"
                    />
                </div>
                <br><br>
                <div>
                    <div class="table-title">Incident Breakdown by Priority</div>
                    <vue-good-table 
                        :columns="incident_breakdown_priorities_column" 
                        :rows="incident_breakdown_prorities_data"
                        styleClass="vgt-table bordered"
                        :sort-options="{
                            enabled: false,
                        }"
                    />
                </div>
                <br><br>
                <div>
                    <div class="table-title">Incident Breakdown by Type</div>
                    <vue-good-table 
                        :columns="incident_breakdown_type_column" 
                        :rows="incident_breakdown_type_data"
                        styleClass="vgt-table bordered"
                        :sort-options="{
                            enabled: false,
                        }"
                    />
                </div>
            </div>
        </div>
        <br><br>
        <div class="w-full h-[1px] bg-slate-500 mt-6 mb-4"></div>
        <div>
            <p class="title font-bold !text-xl">Resource Utilization </p>
            <br>
            <div>
                <div>
                    <div class="table-title">Resource Breakdown by Type</div>
                    <vue-good-table 
                        :columns="resouces_breakdown_type_column" 
                        :rows="resource_breakdown_type_data"
                        styleClass="vgt-table bordered"
                        :sort-options="{
                            enabled: false,
                        }"
                    />
                </div>
            </div>
        </div>
        <br><br>
        <div class="w-full h-[1px] bg-slate-500 mt-6 mb-4"></div>
        <div>
            <p class="title font-bold !text-xl">Responder Performance</p>
            <br>
            <div>
                <div>
                    <div class="table-title">Responder Breakdown by Incident</div>
                    <vue-good-table 
                        :columns="responder_breakdown_type_column" 
                        :rows="responder_breakdown_incident_data"
                        styleClass="vgt-table bordered"
                        :sort-options="{
                            enabled: false,
                        }"
                    />
                </div>
                <br><br>
                <div>
                    <div class="table-title">Responder Breakdown by Response Time </div>
                    <vue-good-table 
                        :columns="responder_breakdown_responder_column" 
                        :rows="responder_breakdown_response_data"
                        styleClass="vgt-table bordered"
                        :sort-options="{
                            enabled: false,
                        }"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Card from "@/components/Card/index.vue";
import axiosIns from "@/library/axios";
import { useInsightReportStore } from '@/stores/insightreport';
import { ref } from "vue";
const insight_report_store = useInsightReportStore();
const incident_overview_column = [
    {
        label:"Summary",
        field:"name"
    },
    {
        label:"Count",
        field:"count"
    },
];
const incident_breakdown_priorities_column = [
    {
        label:"Incident Priority",
        field:"name"
    },
    {
        label:"Count",
        field:"count"
    },
    {
        label:"% of Total",
        field:"total"
    },
    
];
const incident_breakdown_type_column = [
    {
        label:"Incident Type",
        field:"name"
    },
    {
        label:"Count",
        field:"count"
    },
    {
        label:"% of Total",
        field:"total"
    },
    
];
const resouces_breakdown_type_column = [
    {
        label:"Resource Name",
        field:"resource_name"
    },
    {
        label:"Resource Type",
        field:"resource_type"
    },
    {
        label:"Resources Category",
        field:"resource_category"
    },
    
];
const responder_breakdown_type_column = [
    {
        label:"Incident No.",
        field:"incident_no"
    },
    {
        label:"Responder Unit",
        field:"responder_unit"
    },
    {
        label:"Responder Name",
        field:"responder_name"
    },
    
];
const responder_breakdown_responder_column = [
    {
        label:"Incident No.",
        field:"incident_no"
    },
    {
        label:"Incident Type",
        field:"incident_type"
    },
    {
        label:"Responder Unit",
        field:"responder_unit"
    },
    {
        label:"Response Time",
        field:"response_time"
    },
    {
        label:"Resolution Time",
        field:"resolution_time"
    },
    {
        label:"Status",
        field:"status"
    },
    
];
const incident_overview_data = ref([]);
const incident_breakdown_prorities_data = ref([]);
const incident_breakdown_type_data = ref([]);
const resource_breakdown_type_data = ref([]);
const responder_breakdown_incident_data = ref([]);
const responder_breakdown_response_data = ref([]);
export default {
    components:{
        Card
    },
    data(){
        return{
            insight_report_store,
            incident_overview_column,incident_breakdown_priorities_column,incident_breakdown_type_column,
            resouces_breakdown_type_column,responder_breakdown_type_column,responder_breakdown_responder_column,
            incident_overview_data,
            incident_breakdown_prorities_data,
            incident_breakdown_type_data,
            resource_breakdown_type_data,
            responder_breakdown_incident_data,
            responder_breakdown_response_data
        }
    },
    created(){
        insight_report_store.id = this.$route.params.id;
    },
    mounted(){
        insight_report_store.get();
        this.incident_overview();
        this.incident_breakdown_priorities();
        this.incident_breakdown_types();
        this.resource_breakdown_types();
        this.resopnder_breaddown_incident();
        this.resopnder_breaddown_response();
    },
    methods:{
       async incident_overview(){
            try {
                const response = await axiosIns.get("insight-reports/"+this.$route.params.id+"/incident-overview");
                incident_overview_data.value = response.data.data;
            } catch (error) {
                
            }
        },
        async incident_breakdown_priorities(){
            try {
                const response = await axiosIns.get("insight-reports/"+this.$route.params.id+"/incident-breakdown-priorities");
                incident_breakdown_prorities_data.value = response.data.data;
            } catch (error) {
                
            }
        },
        async incident_breakdown_types(){
            try {
                const response = await axiosIns.get("insight-reports/"+this.$route.params.id+"/incident-breakdown-types");
                incident_breakdown_type_data.value = response.data.data;
            } catch (error) {
                
            }
        },
        async resource_breakdown_types(){
            try {
                const response = await axiosIns.get("insight-reports/"+this.$route.params.id+"/resource-breakdown-types");
                resource_breakdown_type_data.value = response.data.data;
            } catch (error) {
                
            }
        },
        async resopnder_breaddown_incident(){
            try {
                const response = await axiosIns.get("insight-reports/"+this.$route.params.id+"/responder-breakdown-incident");
                responder_breakdown_incident_data.value = response.data.data;
            } catch (error) {
                
            }
        },
        async resopnder_breaddown_response(){
            try {
                const response = await axiosIns.get("insight-reports/"+this.$route.params.id+"/responder-breakdown-response");
                responder_breakdown_response_data.value = response.data.data;
            } catch (error) {
                
            }
        },
    }

}
</script>
<style>
    .vgt-table thead th{
        /* padding-top: 0px !important; */
    }
    .title{
        @apply text-[#0B5394] text-2xl;
    }
    .table-title{
        @apply bg-[#0B5394] text-white h-[40px] pl-2
    }
</style>