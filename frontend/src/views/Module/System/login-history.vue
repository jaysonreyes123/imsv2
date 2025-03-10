<template lang="">
    <div>
        <h6>Login History</h6>
        <br>
        <Card>
            <v-select 
                v-model="system_store.login_history.selected" 
                :options="dropdownlist" class="w-80"
                :reduce="option => option.id"
                @option:selected="changeLoginHistory"
                />
            <br>
            <vue-good-table 
                max-height="600px" 
                :columns="columns"
                :rows="system_store.login_history.logs"
                :isLoading.sync="system_store.loading" 
                styleClass="vgt-table condensed lesspadding2" 
                :fixed-header="true"
                :sort-options="{
                    enabled: false,
                }" 
                :pagination-options="{
                    enabled: true,
                    perPage:system_store.login_history.perpage
                }">
                <template #pagination-bottom="props">
                    <div class="py-4 px-3 justify-center flex">
                        <Pagination 
                            :total="system_store.login_history.total" 
                            :current="system_store.login_history.current"
                            :per-page="system_store.login_history.perpage" 
                            @pageChanged="pageChanged"
                        />
                    </div>
                </template>    
            </vue-good-table>
        </Card>
    </div>
</template>
<script>
import Card from "@/components/Card/index.vue";
import Pagination from "@/components/Pagination/index.vue";
import { useDropdownStore } from "@/stores/dropdown";
import { useSystemStore } from "@/stores/system";
const system_store = useSystemStore();
const dropdown_store = useDropdownStore();
export default {
    components:{
        Card,
        Pagination,
    },
    methods:{
        pageChanged(page){
            system_store.login_history.current = page;
            system_store.login_history_logs();
        }
    },
    data(){
        return{
            system_store,
            dropdown_store,
            columns:[
                {
                    label:"User Name",
                    field:"user"
                },
                {
                    label:"IP Address",
                    field:"ipaddress"
                },
                {
                    label:"Sign-in",
                    field:"signin"
                },
                {
                    label:"Sign-out",
                    field:"signout"
                },
                {
                    label:"Status",
                    field:"status"
                }
            ]
        }
    },
    computed:{
        dropdownlist(){
            const all = [{label:"ALL",id:"all"}];
            if(dropdown_store.dropdownlist['users'] !== undefined){
                return [...all,...dropdown_store.dropdownlist['users']];
            }
            return all;
        }
    },
    mounted(){
        dropdown_store.get_dropdown('users');
        system_store.login_history.selected = "all";
        system_store.login_history.logs = [];
        system_store.login_history.current = 1;
        system_store.login_history_logs();
    },
    methods:{
        changeLoginHistory(event){
            system_store.login_history.selected = event.id;
            system_store.login_history_logs();
        }
    }
}
</script>
<style>    
.vgt-table thead th {
  @apply px-2;
}
</style>