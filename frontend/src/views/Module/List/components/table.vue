<template lang="">
    <div>
        <Card title="" class="shadow-sm">
            <div class="flex items-center"
                :class="hasDeleteAll ? 'justify-between' : 'justify-end' "
            >
                <div :class="hasDeleteAll && this.$route.params.module !='insight_reports'  ? 'block' : 'hidden' ">
                    <Button
                        @click="delete_all_btn"
                        :isDisabled="!isDeletedAll"
                        btnClass="btn-outline-danger px-2 py-2 ml-3 mr-4"
                        icon="heroicons-outline:trash"
                    />
                </div>
                <div class="flex" v-if="this.$route.params.module !='insight_reports'">
                    <div class="relative w-[220px] md:w-[400px] mr-2">
                        <span
                            @click="search_function"
                            v-if="!isSearch"
                            class="absolute right-0 top-1/2 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 py-3 pl-3.5 pr-3 text-sm font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400"
                        >
                            <Icon icon="heroicons:magnifying-glass" />
                        </span>
                        <span
                            v-else
                            @click="clearSearch"
                            class="absolute right-0 top-1/2 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 py-3 pl-3.5 pr-3 text-lg font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400"
                        >
                        <Icon class="text-red-800" icon="heroicons-outline:x" />
                        </span>
                        <input
                        type="search"
                        placeholder="Search..."
                        v-model="search"
                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-3 pl-4 pr-[50px] text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                        />
                    </div>
                    <Button
                        v-if="!isFilter && filter "
                        @click="filterModal"
                        text="Filter"
                        btnClass="btn-outline-dark ml-2 py-1 disabled:bg-slate-300 disabled:cursor-not-allowed"
                        icon="heroicons-outline:adjustments-horizontal"
                    />
                    <Button
                        v-else-if="isFilter && filter"
                        @click="clearFilter"
                        text="Remove Filter"
                        btnClass="btn-outline-danger ml-2 py-1 disabled:bg-slate-300 disabled:cursor-not-allowed"
                        icon="heroicons-outline:x"
                    />
                </div>
            </div>
        <vue-good-table 
            class="mt-4"
            styleClass="vgt-table"
            :isLoading.sync="loading"
            :columns="column_header" 
            :row-style-class="rowStyleClassFn"
            v-on:cell-click="onRowClick"
            v-on:selected-rows-change="checkBoxChange"  
            :disable-row-select="true"
            :rows="rows_"
            :select-options="{
                enabled:onSelect,
                selectOnCheckboxOnly:hasAction,
            }"
            :pagination-options="{
                enabled:true,
                perPage:per_page
            }">
            <template v-slot:table-row="props">
                <span v-if="props.column.field == 'incident_statuses.label' && props.row.incident_statuses != null ">
                    <span 
                        class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25"
                        :style="{ 'background': incident_status_color[props.row.incident_statuses.id - 1]+`25`,
                        'color':incident_status_color[props.row.incident_statuses.id - 1] }">
                        {{ props.row.incident_statuses.label }}
                    </span>
                </span>
                <span v-if="props.column.field == 'action'">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <slot name='action' 
                            :report_option="props.row.type"
                            :path="props.row.path"  
                            :id="props.row.id" 
                            :rowIndex="props.row.originalIndex">
                        </slot>
                    </div>
                </span>
            </template>
            <template #pagination-bottom="props">
                <div class="py-4 px-3 flex justify-center">
                    <Pagination :total="total_page" :current="current_page" :per-page="per_page"
                        @page-changed="changePage">
                    </Pagination>
                </div>
            </template>
        </vue-good-table>
        </Card>
    </div>
    <Modal 
        v-if="filter"
            sizeClass="max-w-5xl" title="Filter"
            :activeModal="list_store.modal" 
            @close="closeFilterModal">
            <Filter/>
            <template class="" #footer>
                <div class="w-full px-3 flex justify-between">
                    <Button text="Close" btnClass="text-black" @click="closeFilterModal()" />
                    <Button text="Save Filter" btnClass=" btn-primary py-1" @click="SaveFilter()" />
                </div>
            </template>
        </Modal>
</template>
<script>
import Modal from "@/components/Modal/index.vue";
import Filter from "./filter.vue";
import Button from "@/components/Button/index.vue";
import Icon from "@/components/Icon/index.vue";
import Card from "@/components/Card/index.vue";
import Pagination from "@/components/Pagination/index.vue";
import { useListStore } from "@/stores/list";
import {ref} from "vue";
import { GetColumn } from "../../Other/columns";
const list_store = useListStore();
const isFilter = ref(false);
const isSearch = ref(false);
const isDeletedAll = ref(false);
const delete_all_data = ref([]);
const search = ref("");
const incident_status_color = ['#007bff','#ffc107','#28a745','#6c757d','#dc3545']
export default {
    emits:['changePage','checkbox:selected','row:selected','search',"desearch","delete_all"],
    props:{
        columns:{
            type:Array,
            default:[]
        },
        rows:{
            type:Array,
            default:[]
        },
        hasAction:{
            type:Boolean,
            default:true
        },
        hasDeleteAll:{
            type:Boolean,
            default:false
        },
        filter:{
            type:Boolean,
            default: true
        },
        onSelect:{
            type:Boolean,
            default:true
        },
        onClick:{
            type:Boolean,
            default:true
        },
        isRelation:{
            type:Boolean,
            default:false
        },
        value:String,
        current_page:Number,
        per_page:Number,
        total_page:Number,
        loading:false
    },
    components:{
        Pagination,Card,Icon,Button,Filter,Modal
    },
    computed:{
        column_header(){
            if(this.hasAction){
                const action_header = [{label:'Action',field:'action'}];
                return [...this.columns,...action_header];
            }
            return this.columns;
        },
        rows_(){
            let custom_rows = [];
            if(!this.isRelation){
                custom_rows = this.rows;
            }
            else{
                this.rows.map(item=>{
                    if(item.resources_statuses){
                        Object.assign(item,{disabled:true});
                    }
                    Object.assign(item,{vgtSelected:false})
                    custom_rows.push(item);
                })
            }
            return custom_rows;
        }
    },
    beforeMount() {
        list_store.module = this.$route.params.module;
        list_store.form.filter = [{field:"",type:"text",value:""}];
        this.$watch(
            ()=>this.$route.params.module,
            (new_module) =>{
                isFilter.value = false;
                isSearch.value = false;
                search.value = "";
            }
        )
    },
    data(){
        return{
            list_store,incident_status_color,isSearch,isFilter,search,isDeletedAll
        }
    },
    methods: {
        rowStyleClassFn(row){
            let disabled = row.disabled ? 'disabled-row' : ''
            return `VGT-row ${disabled}`;
        },
        checkBoxChange(event){
            if(this.isRelation){
                event.selectedRows.map(item => {
                    if(item.resources_statuses){
                        // assigned status 
                        if(item.resources_statuses.id == 2){
                            item.vgtSelected=false;
                        }
                    }
                })
            }
            if(event.selectedRows.length > 0){
                isDeletedAll.value = true;
            }
            else{
                isDeletedAll.value = false;
            }
            delete_all_data.value = event.selectedRows;
            this.$emit("checkbox:selected",event.selectedRows);
        },
        delete_all_btn(){
            this.$emit("delete_all",delete_all_data.value);
        },
        onRowClick(row){
            if(row.column.field != 'action'){
                if(this.onClick){
                    this.$router.push({
                        name:'view',
                        params:{
                            module:this.$route.params.module,
                            id:row.row.id
                        }
                    });
                }
                else{
                    this.$emit('row:selected',row);
                }
            }
        },
        changePage(page){
            this.$emit('changePage',page);
        },
        filterModal(){
            list_store.modal = true;
        },
        closeFilterModal(){
            list_store.modal = false;
        },
        SaveFilter(){
            list_store.load();
            list_store.modal = false;
            isFilter.value = true;
        },
        clearFilter(){
            list_store.form.filter = [{field:"",type:"text",value:""}];
            list_store.load();
            isFilter.value = false;
        },
        search_function(){
            if(search.value != ""){
                this.$emit("search",search.value);
                isSearch.value = true;
            }
        },
        clearSearch(){
            this.$emit("desearch",isSearch.value);
            isSearch.value = false;
            search.value = "";
        }
    },
}
</script>
<style>
  .VGT-row:hover{
    @apply cursor-pointer dark:bg-gray-900 bg-gray-100
  }
  .disabled-row{
    pointer-events: none;
  }
</style>