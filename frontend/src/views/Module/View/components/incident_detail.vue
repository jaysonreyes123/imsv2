<template lang="">
    <div>
         <Skeleton class="mt-6" v-if="module_store.loading" :count="5"/>
         <div v-else>
            <Card class="mt-6 shadow">
            <div v-for="(block,blockindex) in module_store.getFieldsSave" :key="blockindex">
                <span class="font-bold mt-5">{{block.title}}</span>
                <br>
                <br>
                <div class="lg:grid gap-x-12 mt-2" style="grid-template-columns: 1fr 1fr;"> 
                    <div v-for="(field,i) in block.fields" :key="i" class="custom-grid-0">
                        <div class="fromGroup relative" v-if="field.link">
                            <label>{{ field.label }}</label>
                            <router-link v-if="module_store.form[field.module]" class="text-blue-500" :to="`/view/${field.module}/${module_store.form[field.module]}`">{{ module_store.form[field.name] }}</router-link>
                        </div>
                        <div class="fromGroup relative" v-else-if="field.type == 'multiselect' ">
                            <label>{{ field.label }}</label>
                            <span>{{ module_store.form[field.name] }}</span>
                        </div>
                        <div class="fromGroup relative" v-else-if="field.type == 'dropdown' ">
                            <label>{{ field.label }}</label>
                            <span>{{ get_dropdown_label(module_store.form[field.name]) }}</span>
                        </div>
                        <div class="fromGroup relative" v-else-if="field.type == 'textarea' ">
                            <label>{{ field.label }}</label>
                            <span style="white-space: pre-line">{{ module_store.form[field.name] }}</span>
                        </div>
                        <div class="fromGroup relative" v-else>
                            <label>{{ field.label }}</label>
                            <span>{{ module_store.form[field.name] }}</span>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            </Card>
         </div>  
    </div>
</template>
<script>
import { useModuleStore } from "@/stores/module";
import Card from "@/components/Card/index.vue";
const module_store = useModuleStore();
export default {
    components:{
        Card,
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
.fromGroup{
  margin-bottom: 15px;
  display: flex;

}
.custom-grid-0{
    grid-column:1
}
.custom-grid-1{
    grid-column:2
}
.fromGroup label{
  overflow-wrap:break-word;
  width: 200px;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
  line-height: 1.5rem;
  text-transform: capitalize;
}
.fromGroup span{
  font-size: 0.875rem;
}
</style>