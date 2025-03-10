<template lang="">
    <div>
        <Modal
            :title="related_store.moduleDetails.label"
            sizeClass="max-w-7xl" 
            :activeModal="related_store.modal" 
            @close="closeModal"
        >
            <select_table v-if="action == 'select'" />
            <saveRelation v-else-if="action == 'add'" />
            <detailRelation v-else-if="action == 'detail'" />
            <template #footer v-if="action == 'select' ">
                <div class="w-full flex justify-between ">
                    <Button @click="closeModal" btnClass="text-dark" text="close" />
                    <Button :isLoading="related_store.loading" btnClass="btn btn-primary px-8" :text="`${action} ${related_store.moduleDetails.label}`" @click="SaveSelect" />
                </div>
            </template>
        </Modal>
    </div>
</template>
<script>
import Button from "@/components/Button/index.vue";
import saveRelation from "./save.vue";
import select_table from "./select_table.vue";
import Modal from "@/components/Modal/index.vue";
import detailRelation from "./detail.vue";
import { useRelatedStore } from "@/stores/related"; 
const related_store = useRelatedStore();
export default {
    props:{
        action:{
            default:"",
            type:String
        }
    },
    components:{
        Modal,select_table,saveRelation,Button,detailRelation
    },
    data(){
        return{
            related_store
        }
    },
    methods:{
        closeModal(){
            related_store.modal = false;
        },
        SaveSelect(){
            const form = {};
            form.selected_row = related_store.modal_list.selected_row;
            form.module = related_store.module;
            form.related_module = related_store.related_module;
            form.id = related_store.id;
            if(related_store.modal_list.selected_row.length > 0){
                related_store.save_selected_row(form);
            }
        }
    }
}
</script>
<style lang="">
    
</style>