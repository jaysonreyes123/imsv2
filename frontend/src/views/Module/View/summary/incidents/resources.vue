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
            :pagination-options="{
                enabled:true,
                perPage:pages['per_page']
            }"
        >
        <template #pagination-bottom="props">
                <div class="py-4 px-3 flex justify-center">
                    <Pagination :total="pages.total" :current="pages.current" :per-page="pages.per_page" @page-changed="pageChanges" />
                </div>
            </template>
        </vue-good-table>
    </div>
</template>
<script>
import Pagination from "@/components/Pagination/index.vue";
import { useRelatedStore } from '@/stores/related';
import { ref } from 'vue';
const related_store = useRelatedStore();
const columns = [
    {
        label:"Resources Name",
        field:"resources_name"
    },
    {
        label:"Status",
        field:"resources_statuses.label"
    },
];
const data = ref([]);
const pages = ref({
    total:0,
    per_page:5,
    current:1
});
const loading = ref(false);
export default {
    components:{
        Pagination
    },
    data(){
        return{
            columns,related_store,data,loading,pages
        }
    },
    mounted(){
        this.load('incidents','resources',this.$route.params.id);
    },
    methods:{
        pageChanges(page){
           pages.value.current = page;
           this.load('incidents','resources',this.$route.params.id,page); 
        },
        async load(module,related_module,id,page = 1){
            try {
                loading.value = true;
                const response = await this.$axios.get("related?page="+page,{
                    params:{
                        id:id,
                        module:module,
                        related_module:related_module,
                        option:0,
                        per_page:5
                    }
                });
               data.value = response.data.data.data;
               pages.value.total = response.data.data.total;
               pages.value.per_page = response.data.data.per_page;
               loading.value = false;
            } catch (error) {
                
            }
        }
    }
}
</script>