<template lang="">
    <div>
        <detail_form :store="module_store" :detailForm="module_store.getFieldsSave" />
    </div>
</template>
<script>
import detail_form from "./detail_form.vue";
import { useModuleStore } from "@/stores/module";
const module_store = useModuleStore();
export default {
    components:{
        detail_form
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
}
</script>