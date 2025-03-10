<template lang="">
    <div>
        <incidentSummary v-if="this.$route.params.module == 'incidents'" />
    </div>
</template>
<script>
import incidentSummary from "./incidents/incidents.vue";
export default {
    components:{
        incidentSummary
    }
}
</script>
<style lang="">
    
</style>