<template lang="">
    <div>
        <Modal 
            sizeClass="max-w-5xl" title="View"
            :activeModal="transcript_store.modal"
            @close="closeModal"
            >
            <div>
                test
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