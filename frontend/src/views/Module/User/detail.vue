<template lang="">
    <div>
        <Skeleton class="mt-6" v-if="user_store.loading" :count="5"/>
        <div  v-else>
            <Card v-for="(block,blockindex) in detailForm" :key="blockindex" :title="block.title" class="mt-6 shadow">
                <div>
                    <div class="lg:grid gap-x-12 mt-2" style="grid-template-columns: 1fr 1fr;"> 
                        <div v-for="(field,i) in block.fields.filter(field => field.name != 'password')" :key="i" :class="`custom-grid-${i%2}`" class="mt-4">
                            <!-- <div class="fromGroup relative" v-if="field.type == 'relation' ">
                                <label>{{ field.label }}</label>
                                <div v-if="field.related_fields">
                                    <a :title="user_store.relation_field[field.name]" :href="`/app/module/${moduleDetails(field.related_fields.related_module).name}/detail/${user_store.form[field.name]}`" 
                                    class="text-blue-600">
                                        {{user_store.relation_field[field.name]}}
                                    </a>
                                </div>
                            </div> -->
                            <div class="fromGroup relative" v-if="field.type == 'checkbox' ">
                                <label>{{ field.label }}</label>
                                <span>
                                    <span  v-if="user_store.form[field.name]" class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-green-600/20 ring-inset">Active</span>
                                    <span v-else class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-red-600/10 ring-inset">Inactive</span>
                                </span>
                            </div>
                            <div class="fromGroup relative" v-else>
                                <label>{{ field.label }}</label>
                                <span>{{ user_store.form[field.name] }}</span>
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
                        <span>{{ user_store.form[field.name] }}</span>
                    </div>
                </div>
            </div>
        </Card>
        </div>
    </div>
</template>
<script>
import Card from "@/components/Card/index.vue";
import { GetFields } from "../Other/fields";
import { useUserStore } from "@/stores/user";
import { useDropdownStore } from "@/stores/dropdown";
import { setForm } from "../Other/fields/mapping";
import { system_generated } from "../Other/fields/system_generate";
const user_store = useUserStore();
const dropdown_store = useDropdownStore();
export default {
    components:{
        Card
    },
    computed:{
        detailForm(){
            return GetFields('users');
        }
    },
    data(){
        return{
            user_store,dropdown_store,system_generated
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
    },
    created(){
        const {form} = setForm(this.detailForm);
        user_store.form = form;
        user_store.id = this.$route.params.id;
    },
    mounted(){
        user_store.get_details();
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