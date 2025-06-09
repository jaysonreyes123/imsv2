<template lang="">
    <div>
        <Modal 
            sizeClass="max-w-5xl" title="View Transcript"
            :activeModal="transcript_store.modal"
            @close="closeModal"
            >
            <div>
                <iframe :src="transcript_store.url" style="width:100%"></iframe>
            </div>
        </Modal>
    </div>
</template>
<script>
import Button from '@/components/Button/index.vue';
import Modal from "@/components/Modal/index.vue";
import { useTranscriptStore } from '@/stores/transcript';
const transcript_store = useTranscriptStore();
export default {
    components:{
        Button,Modal
    },
    data(){
        return{
            transcript_store
        }
    },
    methods:{
        closeModal(){
            transcript_store.modal = false;
        }
    }   
}
</script>
<style lang="">
    
</style>