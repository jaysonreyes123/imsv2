
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
export const useInsightReportStore = defineStore('insightreport', {
    state: () => {
        return {
            loading:false,
            id:"",
            data:{}
        }
    },
    actions: {
        async get(){
           try {
                this.loading = true;
                const response = await axiosIns.get("insight-reports/"+this.id);
                this.data = response.data.data;
                this.loading = false;
           } catch (error) {
            
           };
        },
    },
    persist: true,
})