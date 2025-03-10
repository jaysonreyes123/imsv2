<template lang="">
    <div>
        <detail_form :store="related_store" :detailForm="related_store.getFieldsSave" />
    </div>
</template>
<script>
import { useRelatedStore } from "@/stores/related";
import detail_form from "../../View/components/detail_form.vue";
const related_store = useRelatedStore();
export default {
    components:{
        detail_form
    },
    data(){
        return{
            related_store
        }
    },
    craeted(){
        related_store.module = this.$route.params.module;
        related_store.clear();
        related_store.module = this.$route.params.module;
        related_store.id = this.$route.params.id;
    },
    mounted(){
        related_store.get_detail()
    },
}
</script>