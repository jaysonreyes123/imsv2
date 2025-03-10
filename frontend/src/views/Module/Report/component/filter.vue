<template lang="">
    <div>
        <Card title="Choose List conditions">
            <br>
            <label>All Conditions (All conditions must be met)</label>
            <div class="grid grid-cols-9 gap-8 mt-4" v-for="(item,i) in report_store.form.filter" :key="i">
                <div class="col-start-1 col-span-3">
                    <v-select 
                        @option:selected="filter_and_select_option($event,i)"
                        placeholder="Select an option" 
                        v-model="report_store.form.filter[i].field"
                        :options="select_column_option" 
                        :reduce="option => option.name"
                        :selectable="(option) => !option.header"
                    >
                    <template #option="{ header,label }">
                        <h6 v-if="header" class="font-bold text-[13px] text-slate-600">{{ header }}</h6>
                        <span v-else class="text-[12px]">{{ label }}</span>
                    </template>
                    </v-select>
                </div>
                <div class="col-start-4 col-span-2">
                    <v-select 
                        placeholder="Select an option" 
                        :options="operator" 
                        v-model="report_store.form.filter[i].operator"
                        :reduce="option => option.value"
                    />
                </div>
                <div class="col-start-6 col-span-3">
                    <Textinput v-if="report_store.form.filter[i].type == 'text' || report_store.form.filter[i].type == 'hidden'  " placeholder=""  v-model="report_store.form.filter[i].value" />
                    <Textarea v-else-if="report_store.form.filter[i].type == 'textarea' " v-model="report_store.form.filter[i].value" />
                    <flat-pickr
                        v-else-if="report_store.form.filter[i].type == 'date' "
                        class="form-control"
                        placeholder="yyyy-mm-dd"
                        :config="{ dateFormat:'Y-m-d' }"
                        v-model="report_store.form.filter[i].value"
                    />
                    <flat-pickr
                        v-else-if="report_store.form.filter[i].type == 'time' "
                        class="form-control"
                        placeholder="HH:mm:00"
                        :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i:00',time_24hr:true,minuteIncrement:1 }"
                        v-model="report_store.form.filter[i].value"
                    />
                    <v-select placeholder="Select an option" 
                        v-else-if="report_store.form.filter[i].type == 'dropdown' "
                        :reduce="option => option.id"
                        :options="dropdown_store.dropdownlist[item.field]" 
                        v-model="report_store.form.filter[i].value"
                    />
                    <Textinput v-else :type="report_store.form.filter[i].type" placeholder="" v-model="report_store.form.filter[i].value" />
                </div>
                <div class="flex justify-center ">
                    <button v-if="i !== 0" type="button"
                        class="inline-flex items-center justify-center h-10 w-10 bg-danger-500 text-lg border rounded border-danger-500 text-white"
                        @click="andRemoveCondition(i)">
                        <Icon icon="heroicons-outline:trash" />
                    </button>
                </div>
            </div>
            <Button 
                icon="heroicons-outline:plus" 
                text="Add Condition"
                btnClass="btn-success mr-2 py-2 mt-3"
                @click="andCondition" 
            />
        </Card>
    </div>
</template>
<script>
import Icon from "@/components/Icon/index.vue";
import Textinput from "@/components/Textinput/index.vue";
import Textarea from "@/components/Textarea/index.vue";
import Card from "@/components/Card/index.vue";
import Button from "@/components/Button/index.vue";
import { useReportStore } from '@/stores/report';
import { GetFields } from "../../Other/fields";
import { useDropdownStore } from "@/stores/dropdown";
const report_store = useReportStore();
const dropdown_store = useDropdownStore();
const operator = [
    {
        label:"Equal",
        value:"="
    },
    {
        label:"Not Equal",
        value:"<>"
    }
]
export default {
    components:{
        Card,Button,Textinput,Textarea,Icon
    },
    data(){
        return{
            report_store,dropdown_store,operator
        }
    },
    created(){
        if(report_store.form.filter.length == 0){
            report_store.form.filter = 
            [ 
                {
                    field:"",
                    operator:"",
                    type:"text",
                    value:"",
                    module:""
                }
            ]
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
            if(report_store.form.related_module.length > 0){
                report_store.form.related_module.map(item=>{
                    const related_module_fields =  GetFields(item);
                    related_module_fields.map(item2 =>{
                        let fields = [];
                        item2.fields.map(items3=>{
                            fields.push(Object.assign(items3,{module:item}))
                        })
                        options.push({header:item2.title})
                        options.push(...fields)
                    })
                })
            }
            return options;
        }
    },
    methods:{
        andRemoveCondition(index){
            report_store.form.filter.splice(index,1)
        },
        filter_and_select_option(event,i){
            report_store.form.filter[i].type = event.type;
            report_store.form.filter[i].field = event.name;
            report_store.form.filter[i].operator = "=";
            report_store.form.filter[i].module = event.module;
            if(event.type == 'dropdown'){
                dropdown_store.dropdownlist = [];
                dropdown_store.get_dropdown(event.name);
            }
        },
        andCondition(){
            report_store.form.filter.push(
                {
                    field:"",
                    operator:"",
                    type:"text",
                    value:"",
                    module:""
                }
            );
        },
    }
}
</script>
<style lang="">
    
</style>