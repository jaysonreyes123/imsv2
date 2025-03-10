<template lang="">
    <div class="mt-4">
        <form @submit.prevent="save_reply()">
            <Textarea ref="textarea" v-model="comment_reply" :placeholder="`Reply to ${commentTo}`"/>
            <div class="flex justify-start mt-2" >
                <Button text="Reply" icon="heroicons:chat-bubble-left-ellipsis" btnClass="btn-success btn-sm"  />
            </div>
        </form>
    </div>
</template>
<script>
import { ref } from "vue";
import Textarea from "@/components/Textarea/index.vue";
import Button from "@/components/Button/index.vue";
import Icon from "@/components/Icon/index.vue"
const comment_reply = ref("");
export default {
    props:{
        commentTo:String
    },
    components:{
        Textarea,
        Button,
        Icon
    },
    data(){
        return{
            comment_reply
        }
    },
    mounted(){
        comment_reply.value = "";
        this.$refs.textarea.textarea_focus();
    },
    methods:{
        save_reply(){
            this.$emit("save_reply",comment_reply.value)
        }
    }
}
</script>
<style lang="">
    
</style>