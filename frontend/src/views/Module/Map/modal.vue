<template lang="">
    <div>
        <Modal 
            sizeClass="max-w-5xl"
            title="Marker"
            :activeModal="action_modal"    
            @close="closeModal">

            <div>
                <div>
                    <editMap @updateCoordinate="updateCoordinates" :set_coordinates="form.coordinates"/>
                </div>
                <div class="mt-6">
                    <div v-if="messages.length > 0">
                        <div 
                            v-for="message in messages" 
                            :key="message.label" 
                            class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" 
                            role="alert">
                            {{message.message}}
                        </div>
                    </div>
                    <form @submit.prevent="save">
                        <div v-for="field in fields" :key="field.name">
                            <div  class="fromGroup relative mt-4">
                                <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{field.label}}</label>
                                <Textinput type="text" v-model="form[field.name]" :placeholder="`Enter ${field.label}`"/>
                            </div>
                        </div>
                        <div class="mt-6">
                            <Button type="submit" :isLoading="loading" class="w-full" btnClass="btn-success" text="Save Marker"/>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>
    </div>
</template>
<script>
  import Alert from '@/components/ui/Alert.vue'
import Button from "@/components/Button/index.vue";
import Textinput from "@/components/Textinput/index.vue";
import Modal from "@/components/Modal/index.vue";
import editMap from "./edit-map.vue";
import { ref } from "vue";
import axiosIns from "@/library/axios";
const fields = [
    {
        label:"Name",
        name:"name"
    },
    {
        label:"Location",
        name:"location"
    },
    {
        label:"Link",
        name:"link"
    },
];
const form = ref({
    name:"",
    location:"",
    link:"",
    coordinates:""
});
const loading = ref(false);
const messages = ref([]);
export default {
    emits:['closeModal','closeModalSave'],
    props:['action_modal'],
    components:{
        Modal,editMap,Textinput,Button,Alert
    },
    created(){
    },
    data(){
        return{
            fields,form,loading,messages
        }
    },
    methods:{
        clear(){
            form.value.name = "";
            form.value.coordinates = "";
            form.value.location = "";
            form.value.link = "";
            messages.value = [];
        },
        closeModal(){
            this.$emit("closeModal",false);
        },
        updateCoordinates(event){
            const {lng,lat} = event;
            form.value.coordinates = lng+","+lat;
        },
        async save(){
            try {
                loading.value = true;
                const response = await axiosIns.post("map/marker/save",form.value);  
                loading.value = false;
                this.clear();
                this.$emit("closeModalSave",response.data.data)
            } catch (error) {
                const response = error.response;
                if(response.status == 422){
                    const data = response.data.errors;
                    const keys = Object.keys(data);
                    messages.value = [];
                    keys.map(item =>{
                        messages.value.push({label:item,message:data[item][0]});
                    })
                }
                loading.value = false;
            }
        }
    }
}
</script>
<style lang="">
    
</style>