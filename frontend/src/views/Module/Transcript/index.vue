<template lang="">
    <div class="mt-8">
        <Loading v-model:active="transcript_store.loading_"/>
        <Skeleton class="mt-4" v-if="transcript_store.loading" :count="5"/>
        <Card v-else title="Call Transcript" 
            subtitle="Emergency call transcripts related to this incident"
            subtitleClass="pt-2 !text-slate-500 dark:!text-slate-400">

            <div class="md:flex mt-8 w-full bg-slate-50 dark:bg-slate-700 p-8">
                <div class="w-4/5 space-y-1">
                    <h3 class="font-bold text-lg">Call ID: {{transcript_store.form.call_id}}</h3>
                    <p><span class="font-semibold">Received: </span>{{transcript_store.form.received}}</p>
                    <p><span class="font-semibold">Duration: </span>{{transcript_store.form.duration}}</p>
                    <p>
                        <span class="font-semibold">Status: </span> 
                        <span class="text-green-500" v-if="transcript_store.form.status == 1">Transcript Available</span>
                        <span class="text-red-500" v-else>Transcript Not Available</span>
                    </p>
                </div>
                <div class="md:w-1/2 mt-4" v-if="transcript_store.form.status == 1">
                    <div class="flex gap-4 md:justify-center ">
                        <!-- <Button text="view" 
                        icon="heroicons-outline:eye"
                        btnClass="btn-outline-dark" /> -->
                        <Button 
                            @click="download"
                            text="Download" 
                            icon="heroicons-outline:download"
                            btnClass="btn-dark" 
                        />
                    </div>
                </div>
            </div>
            <!-- <div class="mt-4">
                <p>Caller was first to notice smoke coming from the back of the adjacent building. Provided valuable information about building layout and potenial hazards.</p>
            </div> -->
        </Card>
    </div>
</template>
<script>
import Icon from "@/components/Icon/index.vue";
import Button from "@/components/Button/index.vue";
import Card from "@/components/Card/index.vue";
import { useTranscriptStore } from "@/stores/transcript";
const transcript_store = useTranscriptStore();
export default {
    components:{
        Card,Icon,Button
    },
    data(){
        return{
            transcript_store
        }
    },
    created(){
        transcript_store.id = this.$route.params.id;
    },
    mounted(){
        transcript_store.get();
    },
    methods:{
        download(){
            const title = document.getElementById("breadcrum-subtitle").innerText;
            transcript_store.download(title);
        }
    }
}
</script>
<style lang="">
    
</style>