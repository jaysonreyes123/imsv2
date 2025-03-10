import axiosIns from "@/library/axios";
import router from "@/router";
import { GetFields } from "@/views/Module/Other/fields";
import { setForm, setFormWithData } from "@/views/Module/Other/fields/mapping";
import { system_generated } from "@/views/Module/Other/fields/system_generate";
import { defineStore } from "pinia";
interface formState{
    [index:string] : any;
}
export const useModuleStore = defineStore('module',{
    state:()=>{
        return{
            temporary:{
                module:"",
                id:""
            },
            isLoad:false,
            loading:false,
            module:"",
            id:"",
            modules:[],
            errors:[],
            form:{} as formState
        }
    },
    getters:{
        moduleDetails(state){
            return state.modules.find((m:any) => m.name == state.module);
        },
        getFieldsSave(state){
            return GetFields(state.module)
        }
    },
    actions:{
        clear(){
            this.form = {};
            this.errors = [];
            const {form} = setForm(this.getFieldsSave)
            this.form = form;
        },
        async get_modules(){
            try {
                const response = await axiosIns.get('module/get_modules');
                this.modules = response.data.data;
            } catch (error) {
                
            }
        },
        async save(){
            try {
                this.loading = true;
                let response;
                if(this.form.id == ""){
                    response = await axiosIns.post('module',this.form);
                }
                else{
                    response = await axiosIns.put("module/"+this.id,this.form);
                }
                this.loading = false;
                this.temporary.id = "";
                this.id = response.data.data;
                router.push("/view/"+this.module+"/"+response.data.data);
            } catch (error) {
                
            }
        },
        async edit(){
            try {
                this.loading = true;
                const response = await axiosIns.get("module/edit/"+this.module+"/"+this.id);
                this.form = setFormWithData(this.getFieldsSave,response.data.data);
                this.form.module = this.module;
                this.form.id = this.id;
                this.loading = false;
            } catch (error) {
                
            }
        },
        async get_detail(){
            try {
                this.loading = true;
                const response = await axiosIns.get("module/"+this.module+"/"+this.id);
                this.form = setFormWithData(this.getFieldsSave,response.data.data,'detail');
                system_generated.map((item:any)=>{
                    this.form[item.name] = response.data.data[item.name];
                })
                this.loading = false;
            } catch (error) {
                
            }
        }
    },
    persist:true,
})