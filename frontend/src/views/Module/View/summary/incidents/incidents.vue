<template lang="">
    <div>
        <div class="grid grid-cols-3 mt-6 gap-6">
            <Card title="Incident Summary">
                <Skeleton v-if="module_store.loading"/>
                <div v-else class="grid grid-cols-2 mt-6 text-sm gap-4" v-for="(item,i) in summary_field">
                    <span>{{item.label}}</span>
                    <span v-if="item.type == 'dropdown'">{{get_dropdown_label(module_store.form[item.name])}}</span>
                    <span v-else>{{module_store.form[item.name]}}</span>
                </div>
            </Card>
            <Card title="Media">
                <Media/>
            </Card>
            <Card title="Activity">
                <activitylogs/>
            </Card>
            <Card title="Responder">
                <br>
                <Responder/>
            </Card>
            <Card title="Resources">
                <Resources/>
            </Card>
            <Card title="Task List">
                <Task/>
            </Card>
        </div>
    </div>
</template>
<script>
import Card from "@/components/Card/index.vue";
import Responder from "./responder.vue";
import Resources from "./resources.vue";
import Media from "./media.vue";
import Task from "./task.vue";
import activitylogs from "./activitylogs.vue";
import { useModuleStore } from "@/stores/module";
import { GetFields } from "@/views/Module/Other/fields";
import { get_fields } from "@/views/Module/Other/fields/mapping";
const module_store = useModuleStore();
const summary_field = [];
export default {
    components:{
        Card,Responder,Resources,Media,Task,activitylogs
    },
    data(){
        return{
            module_store,summary_field
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
        const module_fields = GetFields(this.$route.params.module);
        this.summary_field = get_fields(module_fields).filter(field => field.summary);
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
<style lang="">
    
</style>