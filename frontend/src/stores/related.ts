import axiosIns from "@/library/axios";
import { GetFields } from "@/views/Module/Other/fields";
import { setForm, setFormWithData } from "@/views/Module/Other/fields/mapping";
import {module_details} from "@/views/Module/Other/module_details";
import { defineStore } from "pinia";
interface formState{
    [index:string] : any
}
export const useRelatedStore = defineStore('related',{
    state:()=>{
        return{
            modal:false,
            loading:false,
            id:"",
            module:"",
            related_module:"",
            related_id:"",
            modal_list:{
                selected_row:[],
                loading:false,
                data:[],
                current_page:1,
                per_page:15,
                total_page:1 
            },
            search:{
                value:"",
                search_field:[]
            },
            related_list:{
                data:[],
                current_page:1,
                per_page:15,
                total_page:1
            },
            errors:[],
            form:{} as formState
        }
    },
    getters:{
        moduleDetails(state){
            return module_details(state.related_module)
        },
        getFieldsSave(state){
            return GetFields(state.related_module)
        }
    },
    actions:{
        clear_form(){
            this.form = {};
            this.errors = [];
            const {form} = setForm(this.getFieldsSave)
            this.form = form;
            this.form.related_module = "";
            this.form.related_id = "";
            this.loading = false;
        },
        clear(){
            this.related_list.data = [];
            this.related_list.current_page = 1;
        },
        async get_detail(){
            try {
                this.loading = true;
                const response = await axiosIns.get("module/"+this.related_module+"/"+this.related_id);
                this.form = setFormWithData(this.getFieldsSave,response.data.data,'detail');
                this.loading = false;
            } catch (error) {
                this.loading = false;
            }
        },
        async edit(){
            try {
                this.loading = true;
                const response = await axiosIns.get("module/edit/"+this.related_module+"/"+this.related_id);
                this.form = setFormWithData(this.getFieldsSave,response.data.data);
                this.form.id = this.id;
                this.form.module = this.module;
                this.form.related_module = this.related_module;
                this.form.related_id = this.related_id;
                console.log(this.form)
                this.loading = false;
            } catch (error) {
                this.loading = false;
            }
        },
        async save(){
            try {
                this.loading = true;
                if(this.related_id == ""){
                    const response = await axiosIns.post("related",this.form)
                }
                else{
                    const response = await axiosIns.put("related/"+this.related_id,this.form)
                }   
                
                this.loading = false;
                this.load();
                this.modal = false;
            } catch (error) {
                this.loading = false;
            }
        },
        async load(option:number = 0){
            try {
                option == 0 ? this.loading = true : this.modal_list.loading = true;
                const response = await axiosIns.get("related",{
                    params:{
                        id:this.id,
                        module:this.module,
                        related_module:this.related_module,
                        option:option,
                        search:this.search
                    }
                });
                const load_data = response.data.data;
                if(option == 0){
                    this.related_list.data = load_data.data;
                    this.related_list.current_page = load_data.current_page;
                    this.related_list.per_page = load_data.per_page;
                    this.related_list.total_page = load_data.total;
                    this.modal = false;
                }
                else{
                    this.modal_list.data = load_data.data;
                    this.modal_list.current_page = load_data.current_page;
                    this.modal_list.per_page = load_data.per_page;
                    this.modal_list.total_page = load_data.total;
                }
                option == 0 ? this.loading = false : this.modal_list.loading = false;
            } catch (error) {
                this.loading = false;
            }
        },
        async load_single(module:string,related_module:string,option:number = 0){
            try {
                const response = await axiosIns.get("related",{
                    params:{
                        id:this.id,
                        module:module,
                        related_module:related_module,
                        option:option,
                        search:this.search
                    }
                });
                const load_data = response.data.data;
                if(option == 0){
                    this.related_list.data = load_data.data;
                    this.related_list.current_page = load_data.current_page;
                    this.related_list.per_page = load_data.per_page;
                    this.related_list.total_page = load_data.total;
                    this.modal = false;
                }
            } catch (error) {
                this.loading = false;
            }
        },
        async save_selected_row(form:object){
            try {
                this.modal_list.loading = true;
                const response = await axiosIns.post("related/save_selected_row",form);
                this.load();

            } catch (error) {
                this.loading = false;
            }
        },
        async delete(related_id:number,index:number){
            try {
                this.loading = true;
                const response = await axiosIns.get("related/delete/"+this.module+"/"+this.related_module+"/"+this.id+"/"+related_id);
                if(response.data.data == 1){
                    this.load();
                    // this.related_list..splice(index,1);
                }
              
            } catch (error) {
                this.loading = false;
            }
        },
        async checknumber(phone:number){
            try {
                this.loading = true;
                const response = await axiosIns.get("module/checknumber/"+phone);
                this.loading = false;
                return response.data.data;
            } catch (error) {
                this.loading = false;
            }
        }
    },
    persist:true
})