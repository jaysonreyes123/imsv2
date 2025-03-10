<template lang="">
    <div>
        <Breadcrum :title="module_store.moduleDetails.label" subtitle="All">
            <template #button>
                <div class="flex gap-x-2">
                    <div>
                        <router-link v-if="this.$route.params.module !='reports'" :to="`/save/${this.$route.params.module}`">
                            <Button 
                                icon="heroicons-outline:plus" 
                                btnClass="btn-primary shadow-md" 
                                :text="`New ${module_store.moduleDetails.label}`" 
                            />
                        </router-link>
                        <Dropdown v-else :items="dropdown_item" classMenuItems="left-0 top-[110%] ">
                                <Button
                                    :text="`New ${this.$route.params.module}`"
                                    btnClass="btn-primary shadow-md mr-2"
                                    icon="heroicons-outline:plus"
                                    iconPosition="left"
                                    iconClass="text-lg"
                                />
                        </Dropdown>
                    </div>
                    <div v-if="this.$route.params.module != 'users' && this.$route.params.module != 'reports' ">
                        <Button 
                            icon="heroicons-outline:arrow-down-tray"
                            btnClass="btn-outline-primary shadow-md" 
                            text="Import" 
                            @click="openImport"
                        />
                    </div>
                </div>
            </template>
        </Breadcrum>
        <Table
            :rows="list_store.data" 
            :columns="list_store.columns"
            :loading="list_store.loading"
            :current_page="list_store.page.current_page"
            :per_page="list_store.page.per_page"
            :total_page="list_store.page.total_page"
            @changePage="changePage"
            @search = "search"
            @desearch = "desearch"
            >

            <template v-slot:action={id,rowIndex,report_option}>
                <router-link :to="`/view/${this.$route.params.module}/${id}`">
                    <div class="action-btn">
                        <Icon icon="heroicons:eye" />
                    </div>
                </router-link>
                <router-link v-if="this.$route.params.module !='reports'" :to="`/save/${this.$route.params.module}/${id}`">
                    <div class="action-btn text-yellow-500">
                        <Icon icon="heroicons:pencil-square" />
                    </div>
                </router-link>
                <router-link v-else :to="`/reports/save/${report_option}/${id}`">
                    <div class="action-btn text-yellow-500">
                        <Icon icon="heroicons:pencil-square" />
                    </div>
                </router-link>
                <div 
                    class="action-btn text-red-500 cursor-pointer" 
                    @click="del(id,rowIndex)">
                    <Icon icon="heroicons:trash" 
                />
                </div>
            </template>
        </Table>
        <ImportModal/>
    </div>
</template>
<script>
import Dropdown from "@/components/Dropdown/index.vue";
import Breadcrum from "../Other/Breadcrum.vue";
import Button from "@/components/Button/index.vue";
import Icon from "@/components/Icon/index.vue";
import Table from './components/table.vue';
import { useListStore } from '@/stores/list';
import { useModuleStore } from "@/stores/module";
import ImportModal from "@/views/Module/Import/index.vue";
import { useImportStore } from "@/stores/import";
import { GetColumn } from "../Other/columns";
const module_store = useModuleStore();
const list_store = useListStore();
const import_store = useImportStore();
const dropdown_item = [
    {
        label:"List",
        link:"/reports/save/list"
    },
    {
        label:"Chart",
        link:"/reports/save/chart"
    }
]
export default {
    components:{
        Table,Icon,Button,Breadcrum,ImportModal,Dropdown
    },
    data(){
        return{
            list_store,module_store,dropdown_item
        }
    },
    beforeMount(){
        import_store.loading = false;
        list_store.module = this.$route.params.module;
        module_store.module = this.$route.params.module;
    },
    mounted(){
        this.$watch(
            ()=>this.$route.params.module,
            (module)=>{
                list_store.module = module;
                module_store.module = module;
                this.load();
            }
        );
        this.load();
    },
    methods:{
        search(value){
            list_store.form.filter = [];
            list_store.form.search = value;
            const columns = GetColumn(list_store.module);
            list_store.form.search_field = columns.map(item=>{
                if(item.search){
                    return item.field
                }
            }).filter(filter => filter !== undefined)
            list_store.load();
        },
        desearch(value){
            if(value){
                list_store.form.search = "";
                list_store.load();
            }
        },
        closeModal(){
            list_store.modal = false;
        },
        load(){
            list_store.data = [];
            list_store.page.current_page = 1;
            list_store.form.filter = [];
            list_store.form.search = "";
            list_store.load();
        },
        del(id,index){
            this.$swal.fire({
                title: "Delete "+module_store.moduleDetails.label+" ", 
                text: "Are you sure you want to delete this "+module_store.moduleDetails.label+"? ",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                confirmButtonText: "Delete",
                cancelButtonColor: "#3085d6",
                reverseButtons: true, 
                }).then((result) => { 
                    if (result.isConfirmed) {
                        list_store.delete(id);
                    } 
            });
        },
        changePage(pages){
            if(pages != list_store.page.current_page){
                list_store.page.current_page = pages;
                list_store.load();
            }
        },
        openImport(){
            import_store.modal = true;
        }
    }
}
</script>
<style lang="">
    
</style>