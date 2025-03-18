import { defineStore } from "pinia";
import axiosIns from "@/library/axios";
import { GetFields } from "@/views/Module/Other/fields";
import local_dropdownlist from "@/views/Module/Other/Dropdown";
interface dropdownState{
    [index:string] : any
}
export const useDropdownStore = defineStore('dropdown',{
    state:()=>{
        return{
            dropdownloading:[] as dropdownState,
            dropdownlist:[] as dropdownState,
            set_dropdownlist:[] as dropdownState
        }
    },
    actions:{
        async set_dropdown(module:string){
            const fields = GetFields(module);
            fields.map((block:any) => {
                block.fields.map((field:any)=>{
                        if(field.type == 'dropdown' || field.type == 'multiselect'){
                            this.get_local_dropdown(field.name);
                            if(field.option == "get"){
                                this.set_dropdownlist[field.name] = [];
                            }
                        }
                    })
                })
        },
        async get_dropdown(field:string){
            try {
                this.dropdownloading[field] = true;
                const response = await axiosIns.get("dropdown/get_dropdown/"+field);
                this.dropdownlist[field] = response.data.data;
                this.dropdownloading[field] = false;
            } catch (error) {
                
            }
        },

        async get_local_dropdown(field:string){
            const not_sorted = ['incident_types'];
            const dropdown:any =  local_dropdownlist(field);
            if(dropdown == null){
                this.get_dropdown(field);
            }
            else{
                let sort_dropdown = [];
                if(!not_sorted.includes(field)){
                     sort_dropdown = dropdown.sort();
                }   
                else{
                     sort_dropdown = dropdown.sort((a:any,b:any) => a.label > b.label ? 1 : -1 );
                }

                this.dropdownlist[field] = sort_dropdown
            }
        },
    },
    persist:true
})