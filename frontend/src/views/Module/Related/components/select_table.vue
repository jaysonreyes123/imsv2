<template lang="">
    <div>
        <Table
            :columns="list_store.columns" 
            :rows="related_store.modal_list.data" 
            :loading="related_store.modal_list.loading"
            :current_page="related_store.modal_list.current_page"
            :per_page="related_store.modal_list.per_page"
            :total_page="related_store.modal_list.total_page"
            :hasAction="false"
            :filter="false"
            :onClick="false"
            @changePage="changePage"
            @checkbox:selected="checkboxChange"
            @search="search"
            @desearch="desearch"
            >
        </Table>
    </div>
</template>
<script>
import { useRelatedStore } from '@/stores/related';
import Table from '../../List/components/table.vue';
import { useListStore } from '@/stores/list';
import { GetColumn } from '../../Other/columns';
const list_store = useListStore();
const related_store = useRelatedStore();
export default {
    components:{
        Table
    },
    data(){
        return{
            related_store,list_store
        }
    },
    mounted(){
        list_store.module = related_store.related_module;
        related_store.load(1);
    },
    methods:{
        search(value){
            related_store.search.value = value;
            const columns = GetColumn(related_store.related_module);
            related_store.search.search_field = columns.map(item=>{
                if(item.search){
                    return item.field
                }
            }).filter(filter => filter !== undefined)
            related_store.load(1);
        },
        desearch(value){
            if(value){
                related_store.search.value = "";
                related_store.load(1);
            }
        },
        checkboxChange(rows){
            related_store.modal_list.selected_row = rows.map(row => row.id);
        },
        changePage(page){
            
        }
    }
}
</script>
<style lang="">
    
</style>