<template lang="">
    <div>
        <incidentMap v-if="this.$route.params.map == 'incident-map' " />
        <heatMap v-else-if="this.$route.params.map == 'heat-map' " />
    </div>
</template>
<script>
import incidentMap from './incident-map.vue';
import heatMap from './heat-map.vue';
import { useModuleStore } from '@/stores/module';
const module_store = useModuleStore();
export default {
    components:{
        incidentMap,
        heatMap
    },
    beforeMount(){
        module_store.module_form = [];
    }
}
</script>
<style lang="">
    
</style>