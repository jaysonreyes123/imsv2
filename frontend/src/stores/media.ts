import {defineStore} from "pinia"; 
import { useRelatedStore } from "./related";
import axiosIns from "@/library/axios";
const related_store = useRelatedStore();
export const useMediaStore = defineStore('media',{
    state:()=>{
        return{
            loading:false,
            modal:false,
            title:"",
            id:"",
            preview:"",
            total:0,
            current:1,
            per_page:0,
            errors:[
                {
                    filetitle:"",
                    files:"",
                    assigned_to:""

                }
            ],
            form:{
                id:"",
                module:"",
                filetitle:"",
                assigned_to:"",
                note:"",
                files:[]
            },
            mediaList:[],
        }
    },
    actions:{
        async save(){
            try {
                this.loading = true;
                const response = await axiosIns.post('media/save',this.form,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                      }
                });
                this.loading = false;
                this.modal = false;
                related_store.load();
            } catch (error) {
                
            }
        },
        async del(ids:number){
            try {   
                this.loading = true;
                const response = await axiosIns.delete("media/"+ids);
                if(response.data.status == 200){
                   // this.list();
                }
            } catch (error) {
                
            }
        },
        async download(filename:string,extension:string,path:string){
            try {   
                this.loading = true;
                const response = await axiosIns.get("media",{
                    params:{
                        filename:filename,
                        extension:extension,
                        path:path
                    },
                    responseType:"blob"
                });
                const href = URL.createObjectURL(response.data);
                const link = document.createElement('a');
                link.href = href;
                link.setAttribute('download',filename+"."+extension); //or any other extension
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(href);
                this.loading = false;
            } catch (error) {
                
            }
        },
        clearFile(){
            this.form.files = [];
            this.form.assigned_to = "";
            this.form.filetitle = "";
            this.form.note = "";
            this.modal = false;
        }
    },
})