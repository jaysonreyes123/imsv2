<template lang="">
    <div>
        <Skeleton class="mb-4" v-if="module_store.loading" :count="1" />
        <Card v-else class="mb-4" >
            <div>
                <span class="text-lg font-bold">{{module_store.form.incident_no}}</span>
            </div>
            <div class="mt-1">
                <span class="font-bold">Incident type</span> : {{get_dropdown_label(module_store.form.incident_types)}}
            </div>
            <div class="mt-1">
                <span class="font-bold">Status</span> : {{get_dropdown_label(module_store.form.incident_statuses)}}
            </div>
            <div class="mt-1">
                <span class="font-bold">Location</span> : {{module_store.form.location}}
            </div>
        </Card>
    </div>
</template>
<script>
import Card from "@/components/Card/index.vue";
import { useModuleStore } from "@/stores/module";
const module_store = useModuleStore();
export default {
    components:{
        Card
    },
    data(){
        return{
            module_store
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
        }
    }
}
</script>
<style lang="">
    
</style>