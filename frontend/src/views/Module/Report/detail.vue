<template lang="">
    <div>
        <Loading v-model:active="report_store.loading" v-if="report_store.loading"/>
        <Card v-else :title="report_store.form.report_name" class="shadow-lg">
            <template #header>
                <router-link :to="`/reports/save/${report_store.form.option}/${this.$route.params.id}`">
                    <Button 
                        icon="heroicons-outline:pencil-square" 
                        btnClass="btn-primary" 
                        text="Edit" 
                    />
                </router-link>
            </template>
            <ListReport v-if="report_store.form.option == 'list'" />
            <ChartReport v-else />
        </Card>
    </div>
</template>
<script>
import Button from "@/components/Button/index.vue";
import Card from "@/components/Card/index.vue";
import { useReportStore } from "@/stores/report";
import ListReport from "./list.vue";
import ChartReport from "./chart/index.vue";
const report_store = useReportStore();
export default {
    components:{
        ListReport,ChartReport,Card,Button
    },
    data(){
        return{
            report_store
        }
    },
    created(){
        report_store.id = this.$route.params.id;
    },
    mounted(){
        report_store.get(100);
    }
}
</script>
<style lang="">
    
</style>