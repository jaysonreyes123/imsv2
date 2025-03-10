
import axiosIns from "@/library/axios";
import { defineStore } from "pinia";
interface commentState{
    [index:string] : any
}
interface replyContainerState{
    comment?:string,
    updated_at?:string,
    action?:string,
    reply_count:number,
    reply:commentState
}
export const useCommentStore = defineStore('comment', {
    state: () => {
        return {
            loading:false,
            comment_list:[] as commentState,
            comment_reply_list:[],
            current_page:1,
            last_page:0,
            comment_reply_id:0,
            form:{
                id:"",
                module:"",
                module_id:0,
                comment:"",
                comment_id:0,
                action:1
            },
        }
    },
    getters:{

    },
    actions: {
        clear(){
            this.form.comment = "";
            this.comment_reply_id = 0;
        },
        async get(){
            this.loading = true;
            const response = await axiosIns.get("comments/get_comments/"+this.form.module+"/"+this.form.module_id+"?page="+this.current_page);
            this.last_page = response.data.meta.last_page;
            this.comment_list.push(...response.data.data); 
            this.loading = false;
        },
        async delete(commentid:number){
            this.loading = true;
            const response = await axiosIns.get("comments/delete/"+this.form.module+"/comments/"+this.form.id+"/"+commentid);
            this.comment_list = [];
            this.get();
        },
        async get_reply(commentid:number){
            this.loading = true;
            const response = await axiosIns.get("comments/get_comment_replies/"+commentid);
            this.loading = false;
        },
        async save(reply_container:replyContainerState){
           try {
            this.loading = true;
            const response = await axiosIns.post("comments/save",this.form);
            if(!reply_container){
                this.comment_list.unshift(response.data.data);
            }
            else{
                const data = response.data.data;
                if(data.action == 1){
                    console.log(reply_container)
                    reply_container.comment = data.comment;
                    reply_container.action = data.action;
                    reply_container.updated_at = data.updated_at;
                }
                else{
                    const get_reply_count = reply_container.reply_count + 1;
                    reply_container.reply_count = get_reply_count
                    reply_container.reply.unshift(response.data.data);
                }
            }
            this.clear();
            this.loading = false;
           } catch (error) {
            
           }
        },
    },
    persist: true,
})