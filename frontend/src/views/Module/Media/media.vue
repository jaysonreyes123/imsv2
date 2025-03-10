<template lang="">
    <div>
        <Modal 
            sizeClass="max-w-5xl" :title="action == 'add' ? 'Upload Media' : action "
            :activeModal="media_store.modal"
            @close="closeMedia"
            >
            <div v-if="action == 'add'">
                <dropzone/>
            </div>
            <div v-else>
                <iframe  :src="media_store.preview" style="width:100%;min-height:500px" frameborder=0 ></iframe>
            </div>
            <template #footer>
                <div class="w-full flex justify-between">
                    <Button @click="closeMedia" btnClass="text-dark" text="close"/>
                    <Button 
                        v-if="action == 'add'"
                        :disabled="media_store.form.files.length == 0 ? true : false" 
                        :class="media_store.form.files.length == 0 ? 'cursor-not-allowed' : ''" 
                        :isLoading="media_store.loading" @click="upload" text="Save Media"/>
                </div>
            </template>
        </Modal>
    </div>
</template>
<script>
import Button from '@/components/Button/index.vue';
import dropzone from './dropzone.vue';
import Modal from "@/components/Modal/index.vue";
import { useMediaStore } from '@/stores/media';
const media_store = useMediaStore();
export default {
    props:{
        action:{
            type:String,
            default:"add"
        },
    },
    components:{
        Modal,
        dropzone,
        Button
    },
    data(){
        return{
            media_store
        }
    },
    beforeMount(){
        
    },
    methods:{
        closeMedia(){
            media_store.modal = false;
            media_store.errors.filetitle = "";
            media_store.errors.assigned_to ="";
        },
        upload(){
            let error = false;
            if(media_store.form.filetitle == ""){
                media_store.errors.filetitle = "File title is required";
                error = true;
            }
            else{
                media_store.errors.filetitle = "";
            }
            if(media_store.form.assigned_to == ""){
                media_store.errors.assigned_to = "Assigned is required";
                error = true;
            }
            else{
                media_store.errors.assigned_to = "";
            }
            if(!error){
                media_store.save();
            }
        }
    }
}
</script>
<style lang="">
    
</style>