<template lang="">
    <div>
        <form @submit.prevent="save">
            <saveForm :store="related_store" :saveFields="related_store.getFieldsSave"/>
        </form>
    </div>
</template>
<script>
import saveForm from "@/views/Module/Save/form.vue";
import { useDropdownStore } from "@/stores/dropdown";
import { useRelatedStore } from "@/stores/related";
import { GetFields } from "../../Other/fields";
import { setForm } from "../../Other/fields/mapping";
const dropdown_store = useDropdownStore();
const related_store = useRelatedStore();
export default {
    components:{
        saveForm
    },
    data(){
        return{
            related_store,dropdown_store
        }
    },
    created(){  
        related_store.form.module = this.$route.params.module;
    },
    beforeMount(){
        related_store.clear_form();
        dropdown_store.set_dropdown(related_store.related_module);
        related_store.form.module = this.$route.params.module;
        related_store.form.related_module = related_store.related_module;
        related_store.form.id = this.$route.params.id === undefined ? "" : this.$route.params.id;
    },
    mounted(){
        if(related_store.related_id != ""){
            related_store.edit();
        }
    },
    methods:{
        save(){
            const fields = GetFields(this.$route.params.module);
            const {required} = setForm(fields);
            required.map(required => {
                if(related_store.form[required.field] == "" || related_store.form[required.field] === null){
                    related_store.errors[required.field] = `${required.label} is required`;
                }
                else{
                    delete related_store.errors[required.field];
                }
            })
            const errors = Object.keys(related_store.errors);
            if(errors.length == 0){
                console.log(related_store.form)
                related_store.save();
            }
            else{
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }
        }
    }
}
</script>
<style lang="">
    
</style>