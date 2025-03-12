import { defineStore } from "pinia";
import axiosIns from "@/library/axios";
interface activityLogsState{
    logs:Array<any>,
    current_page:number,
    last_page?:number | any,
    is_last_page?:boolean,
    perpage?:number,
    total?:number
}
export const useSystemStore = defineStore('system',{
    state:()=>{
        return{
            loading:false,
            form:{
                id:"",
                module:""
            },
            login_history:{
                selected:"",
                logs:[],
                current_page:1,
                total:0,
                per_page:0,
                last_page:false
            },
            notifications:{
                logs:[],
                current_page:0,
            } as activityLogsState,
            activitylogs:{
                logs:[],
                current_page:1,
                last_page:0,
                is_last_page:false,
            } as activityLogsState,
            
        }
    },
    actions:{
        async notification(){
            try {
                this.loading = true;
                const response = await axiosIns.get('system/notification?page='+this.notifications.current_page);
                this.notifications.logs = response.data.data;
                this.loading = false;
            } catch (error) {
                
            }
        },
        async login_history_logs(){
            try {
                this.loading = true;
                const response = await axiosIns.get("system/login_history?page="+this.login_history.current_page,{
                    params:{
                        id:this.login_history.selected
                    }
                });
                const data = response.data;
                this.login_history.logs = data.data;
                this.login_history.current_page = data.meta.current_page;
                this.login_history.total = data.meta.total;
                this.login_history.per_page = data.meta.per_page;
                if(data.meta.total >= data.meta.current_page){
                    this.login_history.last_page = true;
                }
                console.log(this.login_history)
                this.loading = false;
                
            } catch (error) {
                
            }
        },
        async activity_logs(){
            try {
                this.loading = true;
                const response = await axiosIns.get('system/activity_logs/'+this.form.module+"/"+this.form.id+"?page="+this.activitylogs.current_page);
                this.activitylogs.logs.push(...response.data.data);
                this.activitylogs.last_page = response.data.meta.last_page;
                if(this.activitylogs.current_page < this.activitylogs.last_page){
                    this.activitylogs.is_last_page = false;
                }
                else{
                    this.activitylogs.is_last_page = true;
                }
                this.loading = false;
            } catch (error) {
                
            }
        }
    },
    persist:true
})