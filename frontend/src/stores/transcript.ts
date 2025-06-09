import { defineStore } from "pinia";
import axiosIns from "@/library/axios";
import { GetFields } from "@/views/Module/Other/fields";
import local_dropdownlist from "@/views/Module/Other/Dropdown";
interface dropdownState{
    [index:string] : any
}
export const useTranscriptStore = defineStore('transcript',{
    state:()=>{
        return{
            loading_:false,
            loading:false,
            modal:false,
            id:"",
            module:"",
            url:"",
            form:{}
        }
    },
    actions:{
        async get(){
            try {
                this.loading = true;
                const response = await axiosIns.get('transcripts/get/'+this.id);
                this.form = response.data.data;
                this.loading = false;
            } catch (error) {
                this.loading = false;
            }
        },
        async download(title:string){
            try {
                this.loading_ = true;
                const response = await axiosIns.get("transcripts/download/"+this.id);
                const  url = window.URL.createObjectURL(new Blob([response.data]))
                const a = document.createElement("a");
                a.href = url;
                a.setAttribute('download',`Transcript_${title}.txt`);
                document.body.appendChild(a);
                a.click();
                a.remove();
                this.loading_ = false;
            } catch (error) {
                this.loading_ = false;
            }
        },
        async view(){
            try {
                this.loading_ = true;
                const response = await axiosIns.get("transcripts/view/"+this.id);
                this.url = response.data.data;
                this.modal = true;
                this.loading_ = false;
            } catch (error) {
                this.loading_ = false;
            }
        },
    },
    persist:true
})