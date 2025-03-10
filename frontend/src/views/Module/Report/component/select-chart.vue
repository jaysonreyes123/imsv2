<template lang="">
    <div>
        <Card title="Select chart type">
            <div class="fromGroup relative mt-5">
                <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">Chart type<span class="text-red-500">*</span></label>
                <v-select placeholder="Select an option" :options="chart_type"
                    v-model="report_store.form.chart.type"
                    :reduce="option => option.value" />
                <label class="validation-label" v-if="report_store.errors.type != ''" >{{report_store.errors.type}}</label>
            </div>

            <div class="fromGroup relative mt-5">
                <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">
                    Select Data Fields
                <span class="text-red-500">*</span></label>
                <v-select placeholder="Select an option" :options="data_fields"
                    multiple
                    v-model="report_store.form.chart.dataset"
                    :reduce="option => option.value" />
                    <label class="validation-label" v-if="report_store.errors.dataset != ''" >{{report_store.errors.dataset}}</label>
            </div>

            <div class="fromGroup relative mt-5">
                <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">
                    Select Groupby Field
                <span class="text-red-500">*</span></label>
                <v-select placeholder="Select an option"
                    :reduce="option => option.name"
                    :options="select_column_option"
           
                    @option:selected="selected_groupby"
                    :selectable="(option) => !option.header"
                >
                    <template #option="{ header,label }">
                        <h6 v-if="header" class="font-bold text-[13px] text-slate-600">{{ header }}</h6>
                        <span v-else class="text-[12px]">{{ label }}</span>
                    </template>
                </v-select>
                <label class="validation-label" v-if="report_store.errors.group_by != ''" >{{report_store.errors.group_by}}</label>
            </div>
        </Card>
    </div>
</template>
<script>
import Card from "@/components/Card/index.vue";
import { useReportStore } from '@/stores/report';
import { GetFields } from '../../Other/fields';
const report_store = useReportStore();
const chart_type = [
    {
        value:"pie",
        label:"Pie Chart"
    },
    {
        value:"vertical",
        label:"Vertical Bar Chart"
    },
    {
        value:"horizontal",
        label:"Horizontal Bar Chart"
    },
    {
        value:"line",
        label:"Line Chart"
    },
];
const data_fields = [
    {
        value:"count",
        label:"Record Count"
    }
]
export default {
    components:{
        Card
    },
    data(){
        return{
            chart_type,data_fields,report_store
        }
    },
    computed:{
        select_column_option(){
            const options = [];
            const module_fields =  GetFields(report_store.form.module);
            module_fields.map(item => {
                let fields = [];
                item.fields.map(items2=>{
                    fields.push(Object.assign(items2,{module:report_store.form.module}))
                })
                options.push({header:item.title})
                options.push(...fields)
            })
            // if(report_store.form.related_module.length > 0){
            //     report_store.form.related_module.map(item=>{
            //         const related_module_fields =  GetFields(item);
            //         related_module_fields.map(item2 =>{
            //             let fields = [];
            //             item2.fields.map(items3=>{
            //                 fields.push(Object.assign(items3,{module:item}))
            //             })
            //             options.push({header:item2.title})
            //             options.push(...fields)
            //         })
            //     })
            // }
            return options;
        }
    },
    methods:{
        selected_groupby(event){
            report_store.form.chart.group_by.push({
                label:event.label,
                name:event.name,
                module:event.module
            });
        }
    }
}
</script>
<style lang="">
    
</style>