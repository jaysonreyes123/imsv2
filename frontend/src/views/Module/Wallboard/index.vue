<template lang="">
    <div class="p-5 min-h-screen content-container">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 min-h-[120px]">
            <div class="content-center title md:col-span-2 rounded-md p-4 shadow-md text-center ">
                Incident Management System
            </div>
            <div class="content-center title col-span-1 rounded-md p-4 shadow-md text-center ">
                {{ currentDate }}
            </div>
            <div class="content-center title col-span-1 rounded-md p-4 shadow-md text-center ">
                {{ currentTime }}
            </div>
        </div>
        <br>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 min-h-[500px] md:min-h-[300px]">
            <div v-for="item in incident_statuses" 
            :key="item.label"
            :style="{background:item.background}"
            class="relative text-white rounded-md p-4 shadow-md text-center">
                <div class="absolute font-bold top-[50%] left-[50%] -translate-x-1/2 -translate-y-1/2">
                    <p class="widget-count">{{item.count}}</p>
                    <p class="widget-label">{{item.label}}</p>
                </div>
            </div>
        </div>
        <br>
        <div class="grid grid-cols-2 gap-4">
            <div class="content-center title rounded-md p-4 shadow-md text-center ">
                CALL TAKERS
            </div>
            <div class="content-center title rounded-md p-4 shadow-md text-center ">
                RESPONDERS
            </div>
        </div>
        <br>
        <div class="grid grid-flow-col grid-rows-4 grid-cols-4 gap-4 min-h-[300px]">
            <div v-for="item in calltakers_statuses" :key="item.label" class="col-span-2 row-span-2 content-center title rounded-md p-4 shadow-md text-center ">
                <div class="flex-col-reverse md:flex-row flex items-center w-full">
                    <div class="w-full md:w-1/2 widget-label">{{item.label}}</div>
                    <div class="w-full md:w-1/2 widget-count">{{item.count}}</div>
                </div>
            </div>
            <div v-for="item in responder_statuses" :key="item.label" class="col-span-2 row-span-2 md:col-span-1 md:row-span-4 relative content-center title rounded-md p-4 shadow-md text-center ">
                <div class="absolute font-bold top-[50%] left-[50%] -translate-x-1/2 -translate-y-1/2">
                    <p class="widget-count">{{item.count}}</p>
                    <p class="widget-label">{{item.label}}</p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axiosIns from '@/library/axios';
import { useDashboardStore } from '@/stores/dashboard';
import { ref } from 'vue';
const incident_statuses = ref([
    {
        id:1,
        count:0,
        label:"Open",
        background:"#356EFF"
    },
    {
        id:2,
        count:0,
        label:"In Progress",
        background:"#F6A609"
    },
    {
        id:3,
        count:0,
        label:"Resolved",
        background:"#32BB13"
    },
    {
        id:5,
        count:0,
        label:"On Hold",
        background:"#A80009"
    },
]);
const responder_statuses = ref([
    {
        id:1,
        count:0,
        label:"On Duty",
    },
    {
        id:2,
        count:0,
        label:"Offline",
    },
]);
const calltakers_statuses = ref([
    {
        id:1,
        count:0,
        label:"Available",
    },
    {
        id:0,
        count:0,
        label:"Not Available",
    },
]);
const data = [
    {
        title:"incident Type",
        count:10000
    },
    {
        title:"incident Status",
        count:10000
    },
    {
        title:"incident Priority",
        count:10000
    }
];
const months = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
]
const dashboard_store = useDashboardStore();
export default {
    data(){
        return{
            data,
            currentDate:"",
            currentTime:"",
            incident_statuses,
            responder_statuses,
            calltakers_statuses
        }
    },
    mounted(){
        this.updateTime();
        setInterval(this.updateTime, 1000);
        //every minute
        this.load();
        setInterval(this.load,1000 * 60);
    },
    methods:{
        updateTime() 
        {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const seconds = now.getSeconds();
            const formattedTime = `${hours}:${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
            this.currentTime = formattedTime;
            this.currentDate = `${months[now.getMonth()]} ${now.getDate() < 10 ? '0'+now.getDate() : now.getDate()}, ${now.getFullYear()}`;
        },
        load(){
            console.log("test");
            this.incident_status_widget();
            this.responder_status_widget();
            this.calltakers_status_widget();
        },
        async incident_status_widget(){
           incident_statuses.value.map(async item => {
                try {
                    let response = await axiosIns.get("dashboard/widget/incidents/incident_statuses/=/"+item.id);
                    let finded = incident_statuses.value.find(find_item => find_item.id == item.id)
                    finded.count = response.data.data;
                } catch (error) {
                    
                }
           })
        },
        async responder_status_widget(){
           responder_statuses.value.map(async item => {
                try {
                    let response = await axiosIns.get("dashboard/widget/responders/responder_statuses/=/"+item.id);
                    let finded = responder_statuses.value.find(find_item => find_item.id == item.id)
                    finded.count = response.data.data;
                } catch (error) {
                    
                }
           })
        },
        async calltakers_status_widget(){
            calltakers_statuses.value.map(async item => {
                try {
                    let response = await axiosIns.get("dashboard/widget/call_takers/status/=/"+item.id);
                    let finded = calltakers_statuses.value.find(find_item => find_item.id == item.id)
                    finded.count = response.data.data;
                } catch (error) {
                    
                }
           })
        }       
    }
}
</script>
<style scoped>
    .content-container{
        @apply bg-slate-900
    }
    .content-center{
        @apply flex justify-center items-center bg-[#525050] text-white
    }
    .title{
        @apply text-xl md:text-2xl lg:text-4xl font-bold;
    }
    .widget-label{
        @apply text-xl md:text-3xl lg:text-4xl
    }
    .widget-count{
        @apply text-5xl md:text-7xl lg:text-8xl
    }
</style>