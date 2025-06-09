<template lang="">
    <div>
         <Skeleton class="mt-6" v-if="module_store.loading" :count="5"/>
         <div v-else>
            <div class="grid grid-cols-2 gap-x-4">
                <Card class="mt-6 shadow">
                    <div v-for="(block,blockindex) in module_store.getFieldsSave" :key="blockindex">
                        <div class="mt-4" v-if="block.title != 'Location Details'">
                            <span class="title">{{block.title}}</span>
                            <br><br>
                            <div> 
                                <div v-for="(field,i) in block.fields.filter(x => x.name != 'pbx')" :key="i" >
                                    <div class="fromGroup relative" v-if="field.link">
                                        <label>{{ field.label }}</label>
                                        <router-link v-if="module_store.form[field.module]" class="text-blue-500" :to="`/view/${field.module}/${module_store.form[field.module]}`">{{ module_store.form[field.name] }}</router-link>
                                    </div>
                                    <div class="fromGroup relative" v-else-if="field.type == 'multiselect' ">
                                        <label>{{ field.label }}</label>
                                        <div>{{ module_store.form[field.name] }}</div>
                                    </div>
                                    <div class="fromGroup relative" v-else-if="field.type == 'dropdown' ">
                                        <label>{{ field.label }}</label>
                                        <div>{{ get_dropdown_label(module_store.form[field.name]) }}</div>
                                    </div>
                                    <div class="fromGroup relative" v-else-if="field.type == 'textarea' ">
                                        <label>{{ field.label }}</label>
                                        <div style="white-space: pre-line">{{ module_store.form[field.name] }}</div>
                                    </div>
                                    <div class="fromGroup relative" v-else>
                                        <label>{{ field.label }}</label>
                                        <div>{{ module_store.form[field.name] }}</div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <hr v-if=" blockindex != module_store.getFieldsSave.length -1">
                            <br>
                        </div>
                    </div>
                </Card>
                <Card class="mt-6 shadow">
                    <div v-for="(block,blockindex) in module_store.getFieldsSave" :key="blockindex">
                        <div class="mt-4" v-if="block.title == 'Location Details'">
                            <span class="title">{{block.title}}</span>
                            <br><br>
                            <div class="lg:grid gap-x-12 mt-2"> 
                                <div v-for="(field,i) in block.fields">
                                    <div class="fromGroup relative" v-if="field.type == 'textarea' ">
                                        <label>{{ field.label }}</label>
                                        <div style="white-space: pre-line">{{ module_store.form[field.name] }}</div>
                                    </div>
                                    <div class="fromGroup" v-else>
                                        <label>{{ field.label }}</label>
                                        <div>{{ module_store.form[field.name] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <EditMap :set_coordinates="module_store.form.coordinates"/>
                </Card>
            </div>
         </div>  
    </div>
</template>
<script>
import EditMap from "@/views/Module/Map/edit-map.vue";
import { useModuleStore } from "@/stores/module";
import Card from "@/components/Card/index.vue";
const module_store = useModuleStore();
export default {
    components:{
        Card,EditMap
    },
    data(){
        return{
            module_store
        }
    },
    created(){
        if(module_store.temporary.module == this.$route.params.module && module_store.temporary.id == this.$route.params.id){
            module_store.isLoad = false;
        }
        else{
            module_store.temporary.module = this.$route.params.module;
            module_store.temporary.id = this.$route.params.id;
            module_store.isLoad = true;
            module_store.module = this.$route.params.module;
            module_store.clear();
            module_store.module = this.$route.params.module;
            module_store.id = this.$route.params.id;
        }
    },
    mounted(){
        if(module_store.isLoad){
            module_store.get_detail()
        }
    },
    methods:{
        get_dropdown_label(data){
            let label = "";
            if(data != "" && data != null){
                if(label !== undefined){
                    label = data.label;
                }
            }
            return label;
        },
    }
}
</script>
<style scoped>
label{
  font-weight: bold;
}
.title{
    @apply dark:text-slate-100 text-slate-700
}
.fromGroup{
  margin-bottom: 15px;
  @apply md:grid grid-cols-3 w-full;
}
.fromGroup div{
    word-break: break-all;
    @apply col-span-2
}
.custom-grid-0{
    grid-column:1
}
.custom-grid-1{
    grid-column:2
}
.fromGroup label{
  overflow-wrap:break-word;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
  text-transform: capitalize;
}
.fromGroup div{
  font-size: 0.875rem;
}
</style>