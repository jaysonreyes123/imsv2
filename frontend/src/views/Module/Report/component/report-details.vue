<template lang="">
    <div class="mt-6">
        <div class="grid lg:grid-cols-4 grid-cols-2 ">
            <span>Report Name<span class="text-red-500">*</span></span>
            <div>
                <Textinput v-model="report_store.form.report_name" placeholder="Report Name"/>
                <label class="validation-label" v-if="report_store.errors.report_name !=''" >{{report_store.errors.report_name}}</label>
            </div>
        </div>
        <br>
        <div class="grid lg:grid-cols-4 grid-cols-2">
            <span>Module<span class="text-red-500">*</span></span>
            <div>
                <v-select placeholder="Select an option" v-model="report_store.form.module" @option:selected="select_module" :reduce="option => option.name" :options="module_option" />
                <label class="validation-label" v-if="report_store.errors.module !=''" >{{report_store.errors.module}}</label>
            </div>
        </div>
        <br>
        <div class="grid lg:grid-cols-4 grid-cols-2">
            <span>Relation Module (<i>Optional</i> )</span>
            <div>
                <v-select placeholder="Select an option"  multiple v-model="report_store.form.related_module" :reduce="option => option.name" :options="related_menu_option" />
            </div>
        </div>
    </div>
</template>
<script>
import Textinput from "@/components/Textinput/index.vue";
import { useModuleStore } from "@/stores/module";
import related_menu from "../../Other/related_menu";
import { ref } from "vue";
import { useReportStore } from "@/stores/report";
const report_store = useReportStore();
const module_store = useModuleStore();
const related_menu_option = ref([]);
export default {
    components:{
        Textinput
    },
    computed:{
        module_option(){
            const module_ = module_store.modules;
            return module_.filter(option => option.presence == 1 || option.presence == 2)
        }
    },
    data(){
        return{
           report_store,related_menu_option
        }
    },
    methods:{
        select_module(event){
            report_store.form.related_module = [];
            related_menu_option.value = related_menu(event.name);
        }
    }
}
</script>