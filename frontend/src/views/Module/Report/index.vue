<template lang="">
    <div>
        <Loading v-model:active="report_store.loading"/>
        <Breadcrum
            title="Report"
            :subtitle="this.$route.params.id == '' ? 'Adding New' : 'Editing' "
        />
        <form @submit.prevent="save">
            <Card class="shadow-lg">
                <div class="flex z-[5] items-center relative justify-center md:mx-8">
                    <div
                        class="relative z-[1] items-center item flex flex-start flex-1 last:flex-none group"
                        v-for="(item, i) in stepper"
                        :key="i">

                        <div
                        :class="`   ${
                            steps >= i
                            ? 'bg-slate-900 text-white ring-slate-900 ring-offset-2 dark:ring-offset-slate-500 dark:bg-slate-900 dark:ring-slate-900'
                            : 'bg-white ring-slate-900 ring-opacity-70  text-slate-900 dark:text-slate-300 dark:bg-slate-600 dark:ring-slate-600 text-opacity-70'
                        }`"
                        class="transition duration-150 icon-box md:h-12 md:w-12 h-7 w-7 rounded-full flex flex-col items-center justify-center relative z-[66] ring-1 md:text-lg text-base font-medium"
                        >
                        <span v-if="steps <= i"> {{ i + 1 }}</span>
                        <span v-else class="text-3xl">
                            <Icon icon="bx:check-double" />
                        </span>
                        </div>
                        <div
                        class="absolute top-1/2 h-[2px] w-full"
                        :class="
                            steps >= i
                            ? 'bg-slate-900 dark:bg-slate-900'
                            : 'bg-[#E0EAFF] dark:bg-slate-700'
                        "
                        ></div>
                        <div
                        class="absolute top-full text-base md:leading-6 mt-3 transition duration-150 md:opacity-100 opacity-0 group-hover:opacity-100"
                        :class="
                            steps >= i
                            ? ' text-slate-900 dark:text-slate-300'
                            : 'text-slate-500 dark:text-slate-300 dark:text-opacity-40'
                        "
                        >
                        <span class="w-max">{{ item.title }}</span>
                        </div>
                    </div>
                </div>
                <div class="conten-box mt-14 border-t border-slate-100 dark:border-slate-700 -mx-6 px-6 pt-6">
                    <div v-if="steps == 0">
                        <reportDetails/>
                    </div>
                    <div v-else-if="steps == 1">
                        <select_columns @option:selected="selected_column" @option:deselected="deselected_column" v-if="this.$route.params.option == 'list'"/>
                        <Filter v-else/>
                    </div>
                    <div v-else-if="steps == 2">
                        <Filter v-if="this.$route.params.option == 'list'"/>
                        <SelectChart v-else/>
                    </div>
                </div>
                <br><br>
                <div class="flex border-t-2 border-slate-100" :class="steps > 0 ? 'justify-between' : ' justify-end'" >
                    <Button
                        v-if="steps != 0"
                        text='Back'
                        @click.prevent="back()"
                        btnClass="btn-outline-dark px-8 mt-4"
                    />
                    <Button
                        type="submit"
                        :text="steps !== stepper.length - 1 ? 'next' : 'Save Report'"
                        btnClass="btn-primary px-8 mt-4"
                    />
                </div>
            </Card>
        </form>
    </div>
</template>
<script>
import Breadcrum from "../Other/Breadcrum.vue";
import Filter from "./component/filter.vue";
import SelectChart from "./component/select-chart.vue";
import select_columns from "./component/select_columns.vue";
import reportDetails from "./component/report-details.vue";
import Button from "@/components/Button/index.vue";
import Icon from "@/components/Icon/index.vue";
import Card from "@/components/Card/index.vue";
import { ref } from 'vue';
import { useReportStore } from "@/stores/report";
const report_store = useReportStore();
const list_step =  
[
    {
        id: 1,
        title: "Report Details",
    },
    {
        id: 2,
        title: "Select Columns",
    },
    {
        id: 3,
        title: "Filters",
    },
]
const chat_step =  
[
    {
        id: 1,
        title: "Report Details",
    },
    {
        id: 2,
        title: "Filter",
    },
    {
        id: 3,
        title: "Select Chart",
    },
]
const steps = ref(0);
export default {
    components:{
        reportDetails,select_columns,Filter,SelectChart,
        Icon,Card,Button,Breadcrum
    },
    data(){
        return{
            list_step,
            report_store,
            steps
        }
    },
    computed:{
        stepper(){
            return this.$route.params.option == 'list' ? list_step : chat_step
        }
    },
    created(){
        this.clear();
        report_store.id = this.$route.params.id === undefined ? "" : this.$route.params.id;
        report_store.form.option = this.$route.params.option;
        report_store.loading = false;
    },
    mounted(){
        if(report_store.id != ""){
            report_store.edit();
        }
    },
    methods:{
        selected_column(event){
            report_store.form.selected_columns = event;
        },
        deselected_column(event){
            report_store.form.selected_columns = event;
        },
        back(){
            steps.value--;
        },
        save(){

            if(steps.value == 0){
                let error = false;
                if(report_store.form.report_name == ""){
                    report_store.errors.report_name = "Report name is required";
                    error = true;
                }
                else{
                    report_store.errors.report_name = "";
                }
                if(report_store.form.module == ""){
                    report_store.errors.module = "Module is required";
                    error = true;
                }
                else{
                    report_store.errors.module = "";
                }
                if(error)return false;
            }
            else if(steps.value == 1){
                if(report_store.selected_col.length == 0 && report_store.form.option == 'list'){
                    report_store.errors.selected_columns = "Select at least one column";
                    return false;
                }
                else{
                    report_store.errors.selected_columns = "";
                }
            }
            else if(steps.value == 2){
                let error = false;
                if(report_store.form.option == 'chart'){
                    if(report_store.form.chart.type == ''){
                        report_store.errors.type = "Chart type is required";
                        error = true;
                    }
                    else{
                        report_store.errors.type = "";
                    }
                    if(report_store.form.chart.dataset.length  == 0){
                        report_store.errors.dataset = "Data Field is required";
                        error = true;
                    }
                    else{
                        report_store.errors.dataset = "";
                    }
                    if(report_store.form.chart.group_by  == ''){
                        report_store.errors.group_by = "Group By is required";
                        error = true;
                    }
                    else{
                        report_store.errors.group_by = "";
                    }

                }
                if(error){return false;}
                report_store.save();
                return;
            }
            steps.value++;
        },
        clear(){
            report_store.selected_col = [];
            report_store.form.report_name = "";
            report_store.form.related_module = []
            report_store.form.selected_columns = []
            report_store.form.filter = [];
            report_store.form.module = '';
            report_store.form.chart.dataset=[];
            report_store.form.chart.group_by = [];
            report_store.form.chart.type = "";
            report_store.errors.module = "";
            report_store.errors.report_name = "";
            steps.value = 0;
        },
    }
}
</script>
<style>
.validation-label{
    @apply text-danger-500 block text-sm mt-2
}
</style>