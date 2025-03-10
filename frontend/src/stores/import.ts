import { defineStore } from "pinia";
import axiosIns from "@/library/axios";
interface formSate{
    step:number,
    module:string,
    file:any,
    hasheader:boolean,
    duplicate_option:number,
    duplicate_field:any,
    fields:any
}
export const useImportStore = defineStore('import',{
    state:()=>{
        return{
            modal:false,
            loading:false,
            module_fields:[],
            import_data:[],
            step:0,
            form:{
                module:"",
                file:"",
                hasheader:false,
                duplicate_option:1,
                duplicate_field:[],
                fields:[]
            } as formSate,
        }
    },
    getters:{
        module_field_option(state){
            return state.module_fields.map((item:any)=>{
                return{
                    value:item.name,
                    label:item.label,
                    required:item.required,
                    type:item.type
                }
            })
        }
    },
    actions:{
       async get_module_fields(){
            try {
                this.loading = true;
                const response = await axiosIns.get('module/get_fields/'+this.form.module);
                this.module_fields = response.data.data;
                this.loading = false;

            } catch (error) {
                
            }
       },
       async getImport(){
            try {
                this.loading = true;
                const response = await axiosIns.post("import/import_data",this.form,{
                    headers:{
                        'Content-type' : 'multipart/form-data'
                    }
                })
                this.import_data = response.data.data;
                this.import_data.map(item=>{
                    this.form.fields.push({field:"",type:"",position:""})
                })
                this.loading = false;
                this.step++;
            } catch (error) {
                this.loading = false;
            }
       },
       clear(){
            this.form.file = "";
            this.form.duplicate_field = [];
            this.form.fields = [];
            this.form.hasheader = false;
            this.form.duplicate_option = 1;
       },
       async saveImport(){
            try {
                this.loading = true;
                const response = await axiosIns.post("import/save",this.form,{
                    headers:{
                        'Content-type' : 'multipart/form-data'
                    }
                })
                this.import_data = response.data.data;
                this.loading = false;
                this.step++;
            } catch (error) {
                this.loading = false;
            }
        },
    },
    persist:true
})