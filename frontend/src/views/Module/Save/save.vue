<template lang="">
    <div>
        <Loading v-model:active="module_store.loading"/>
        <form  @submit.prevent="save">
            <saveForm :store="module_store" :saveFields="module_store.getFieldsSave" />
        </form>
    </div>
</template>
<script>
import saveForm from "./form.vue";
import { useModuleStore } from "@/stores/module";
import { setForm } from "../Other/fields/mapping";
import { useDropdownStore } from "@/stores/dropdown";
import { GetFields } from "../Other/fields";
import { emailValidation } from "./validation";
const module_store = useModuleStore();
const dropdown_store = useDropdownStore();
export default {
    components:{
        saveForm
    },
    data(){
        return{
            module_store,dropdown_store
        }
    },
    created(){
        module_store.clear();
        module_store.id = this.$route.params.id === undefined ? "" : this.$route.params.id;
        module_store.form.id = this.$route.params.id === undefined ? "" : this.$route.params.id;
        module_store.module = this.$route.params.module;
        module_store.form.module = this.$route.params.module;
       
    },
    beforeMount(){
    },
    mounted(){
        dropdown_store.set_dropdown(this.$route.params.module);
        if(module_store.id != ""){
            module_store.edit();
        }
        else{
            module_store.temporary.id = "";
        }
    },
    methods:{
        save(){
            const fields = GetFields(this.$route.params.module);
            const {required} = setForm(fields);
            required.map(required => {
                if(module_store.form[required.field] == "" || module_store.form[required.field] === null){
                    module_store.errors[required.field] = `${required.label} is required`;
                }
                else{
                    delete module_store.errors[required.field];
                }
            })
            const errors = Object.keys(module_store.errors);
            if(errors.length == 0){
                module_store.save();
            }
            else{
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }
        }
    }
}
</script>
