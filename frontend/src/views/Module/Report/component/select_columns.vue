<template lang="">
    <div>
        <div class="fromGroup relative mt-5">
            <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">Select Columns(MAX 10)<span class="text-red-500">*</span></label>
            <v-select 
                placeholder="Select an option"
                class="mt-3"
                multiple 
                :options="select_column_option"
                :reduce="option => option.name"
                v-model="report_store.selected_col"
                @option:selected="selected_option"
                @option:deselected="deselected_option"
                :closeOnSelect="false" 
                :selectable="(option)=> 
                report_store.selected_col.length < 11 && 
                !report_store.selected_col.includes(option.name) && 
                !option.header "
            >
                <template #option="{ header,label }">
                    <h6 v-if="header" class="font-bold text-[13px] text-slate-600">{{ header }}</h6>
                    <span v-else class="text-[12px]">{{ label }}</span>
                </template>
            </v-select>
            <label class="validation-label" v-if="report_store.errors.selected_columns != ''" >{{report_store.errors.selected_columns}}</label>
        </div>
    </div>
</template>
<script>
import { useReportStore } from '@/stores/report';
import { GetFields } from '../../Other/fields';
const report_store = useReportStore();
var selected_column = [];
export default {
    emits:["option:selected","option:deselected"],
    data(){
        return{
            report_store
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
                options.push({header:item.title,label:item.title})
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
                        options.push({header:item2.title,label:item.title})
                        options.push(...fields)
                    })
                })
            }
            return options;
        }
    },
    methods:{
        selected_option(event){
            selected_column = [];
            event.map(item=>{
                selected_column.push(
                    {
                        label:item.label,
                        name:item.name,
                        module:item.module
                    }
                );
            })
            this.$emit("option:selected",selected_column);
        },
        deselected_option(event){
            const index = selected_column.findIndex(col => col.name == event.name);
            selected_column.splice(index,1);
            this.$emit("option:deselected",selected_column);
        }
    }
}
</script>
<style lang="">
    
</style>