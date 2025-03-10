<template lang="">
    <div>
        <br>
        <Skeleton v-if="loading" />
        <vue-good-table
            v-else
            styleClass="vgt-table"
            :columns="columns"
            :rows="data"
            :sort-options="{
                enabled:false
            }"
        />
    </div>
</template>
<script>
import { useRelatedStore } from '@/stores/related';
import { ref } from 'vue';
const related_store = useRelatedStore();
const columns = [
    {
        label:"File Title",
        field:"filetitle"
    },
    {
        label:"Note",
        field:"note"
    },
];
const data = ref([]);
const loading = ref(false);
export default {
    data(){
        return{
            columns,related_store,data,loading
        }
    },
    mounted(){
        this.load('incidents','media',this.$route.params.id);
    },
    methods:{
        async load(module,related_module,id){
            try {
                loading.value = true;
                const response = await this.$axios.get("related",{
                    params:{
                        id:id,
                        module:module,
                        related_module:related_module,
                        option:0,
                    }
                });
               data.value = response.data.data.data;
               loading.value = false;
            } catch (error) {
                
            }
        }
    }
}
</script>