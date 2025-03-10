<template lang="">
    <div>
        <Breadcrum
            :title="moduleDetails.label"
            :subtitle="this.$route.params.id == '' ? 'Adding New' : 'Editing' "
        />
        <saveUser v-if="this.$route.params.module =='users'"/>
        <saveReport v-else-if="this.$route.params.module =='reports'"/>
        <saveModule v-else/>
    </div>
</template>
<script>
import Breadcrum from "../Other/Breadcrum.vue";
import saveReport from "@/views/Module/Report/index.vue";
import saveUser from "@/views/Module/User/save.vue";
import saveModule from "./save.vue";
import { module_details } from "../Other/module_details";
export default {
    components:{
        saveModule,saveUser,saveReport,Breadcrum
    },
    computed:{
        moduleDetails(){
            return module_details(this.$route.params.module);
        }
    }
}
</script>
<style lang="">
    
</style>