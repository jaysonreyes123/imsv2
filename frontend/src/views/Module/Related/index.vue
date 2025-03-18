<template lang="">
    <div>
        <Card :title="moduleDetails.label" class="mt-8">
            <template #header>
                <div class="flex justify-end">
                    <div v-for="item in action_button" :key="item">
                        <Button
                            v-if="item == 'add'"
                            icon="heroicons-outline:plus"
                            :text="`New ${moduleDetails.label}`"
                            btnClass="btn-primary mr-2 py-2"
                            @click="related_module == 'media' ? openMediaModal() : openSaveRelatedModal()"
                        />
                        <Button
                            v-if="item == 'select'"
                            icon="heroicons-outline:plus"
                            :text="`Select ${moduleDetails.label}`"
                            btnClass="btn-primary mr-2 py-2"
                            @click="openSelectRelatedModal"
                        />
                    </div>
                </div>
            </template>
            <br>
            <Table 
                :loading="related_store.loading"
                :columns="column" 
                :rows="related_store.related_list.data" 
                :current_page="related_store.related_list.current_page"
                :per_page="related_store.related_list.per_page"
                :total_page="related_store.related_list.total_page"
                :filter="false"
                :onClick="false"
                @search="search"
                @desearch="desearch"
            >
            <template v-slot:action={id,rowIndex,path}>
                    <div v-if="related_store.related_module == 'media'">
                        <a @click="preview(path)">
                            <div class="action-btn">
                                <Icon icon="heroicons:photo" />
                            </div>
                        </a>
                    </div>
                    <!-- <div v-if="related_module == 'media'">
                        <a @click="download(path)">
                            <div class="action-btn">
                                <Icon icon="heroicons:document-arrow-down" />
                            </div>
                        </a>
                    </div> -->
                    <div v-if="related_store.related_module != 'media'" @click="view(id)" class="action-btn">
                        <Icon icon="heroicons:eye" />
                    </div>
                    <div v-if="related_store.related_module != 'media'" @click="edit(id)" class="action-btn text-yellow-500">
                        <Icon icon="heroicons:pencil-square" />
                    </div>
                    <div 
                        class="action-btn text-red-500 cursor-pointer" 
                        @click="del(id,rowIndex)">
                        <Icon icon="heroicons:trash" 
                    />
                    </div>
            </template>
            </Table>
            <Media :action="action"/>
            <RelatedModal :action="action"/>
        </Card>
    </div>
</template>
<script>
import Icon from "@/components/Icon/index.vue";
import Button from "@/components/Button/index.vue";
import Card from "@/components/Card/index.vue";
import {module_details} from '../Other/module_details';
import Table from '../List/components/table.vue';
import Media from "../Media/media.vue";
import RelatedModal from "./components/modal.vue";
import { ref } from "vue";
import { useMediaStore } from "@/stores/media";
import { GetColumn } from "../Other/columns";
import { useRelatedStore } from "@/stores/related";
const media_store = useMediaStore();
const related_store = useRelatedStore();
const action = ref("");
export default {
    props:{
        related_module:String,
        action_button:{
            type:Array,
            default:[]
        }
    },
    components:{
        Media,RelatedModal,
        Table,Icon,Card,Button
    },
    data(){
        return{
            action,related_store
        }
    },
    computed:{
        moduleDetails(){
            return module_details(this.related_module);
        },
        column(){
            return GetColumn(this.related_module);
        }
    },
    beforeMount(){
        related_store.clear();
        related_store.module = this.$route.params.module;
        related_store.related_module = this.related_module;
        related_store.id = this.$route.params.id;
    },
    mounted(){
        related_store.load();
    },
    methods:{
        search(value){
            related_store.search.value = value;
            const columns = GetColumn(this.related_module);
            related_store.search.search_field = columns.map(item=>{
                if(item.search){
                    return item.field
                }
            }).filter(filter => filter !== undefined)
            related_store.load();
        },
        desearch(value){
            if(value){
                related_store.search.value = "";
                related_store.load();
            }
        },
        openMediaModal(){
            media_store.modal = true;
            action.value = 'add';
        },
        openSaveRelatedModal(){
            action.value = "add";
            related_store.related_id = '';
            related_store.modal = true;

        },
        openSelectRelatedModal(){
            action.value = "select";
            related_store.modal = true;
        },
        preview(path){
            media_store.preview = path;
            media_store.modal = true;
            action.value = 'preview';
        },
        view(related_id){
            related_store.related_id = related_id;
            action.value = 'detail';
            related_store.modal = true;
        },
        edit(related_id){
            related_store.related_id = related_id;
            action.value = 'add';
            related_store.modal = true;
        },
        del(id,index){
            this.$swal.fire({
            title: "Unlink "+related_store.moduleDetails.label+" ", 
            text: "Are you sure you want to unlink this "+related_store.moduleDetails.label+"? ",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Unlink",
            cancelButtonColor: "#3085d6",
            reverseButtons: true, 
            }).then((result) => { 
                if (result.isConfirmed) {
                    related_store.delete(id,index);
                } 
            });
        },
    }
}
</script>
<style lang="">
    
</style>