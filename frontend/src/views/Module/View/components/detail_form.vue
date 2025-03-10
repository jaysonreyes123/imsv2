<template lang="">
    <div>
        <Skeleton class="mt-6" v-if="store.loading" :count="5"/>
        <div  v-else>
            <Card v-for="(block,blockindex) in detailForm" :key="blockindex" :title="block.title" class="mt-6 shadow">
                <div v-if="block.title == 'Location Details'">
                    <div class="lg:grid lg:grid-cols-2 gap-12 mt-6">
                        <div>
                            <div class="fromGroup relative" v-for="(field,i) in block.fields" :key="i">
                            <label for="">{{field.label}}</label>
                                <span>{{ store.form[field.name] }}</span>
                            </div>
                        </div>
                        <div>
                            <EditMap :set_coordinates="store.form.coordinates" />
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="lg:grid gap-x-12 mt-2" style="grid-template-columns: 1fr 1fr;"> 
                        <div v-for="(field,i) in block.fields" :key="i" :class="`custom-grid-${i%2}`" class="mt-4">
                            <!-- <div class="fromGroup relative" v-if="field.type == 'relation' ">
                                <label>{{ field.label }}</label>
                                <div v-if="field.related_fields">
                                    <a :title="store.relation_field[field.name]" :href="`/app/module/${moduleDetails(field.related_fields.related_module).name}/detail/${store.form[field.name]}`" 
                                    class="text-blue-600">
                                        {{store.relation_field[field.name]}}
                                    </a>
                                </div>
                            </div> -->
                            <div class="fromGroup relative" v-if="field.type == 'multiselect' ">
                                <label>{{ field.label }}</label>
                                <span>{{ store.form[field.name] }}</span>
                            </div>
                            <div class="fromGroup relative" v-else-if="field.type == 'dropdown' ">
                                <label>{{ field.label }}</label>
                                <span>{{ get_dropdown_label(store.form[field.name]) }}</span>
                            </div>
                            <div class="fromGroup relative" v-else>
                                <label>{{ field.label }}</label>
                                <span>{{ store.form[field.name] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </Card>
        <Card title="System Generated" class="mt-4">
            <div class="lg:grid gap-x-12 mt-4" style="grid-template-columns: 1fr 1fr;"> 
                <div v-for="(field,i) in system_generated">
                    <div class="fromGroup relative">
                        <label>{{ field.label }}</label>
                        <span>{{ store.form[field.name] }}</span>
                    </div>
                </div>
            </div>
        </Card>
        </div>
    </div>
</template>
<script>
import Card from "@/components/Card/index.vue";
import EditMap from "@/views/Module/Map/edit-map.vue";
import { system_generated } from "../../Other/fields/system_generate";
export default {
    props:['store','detailForm'],
    components:{
        Card,EditMap
    },
    data(){
        return{
            system_generated
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