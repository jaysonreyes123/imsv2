import axiosIns from "@/library/axios";
import router from "@/router";
import { GetFields } from "@/views/Module/Other/fields";
import { get_fields, setForm, setFormWithData } from "@/views/Module/Other/fields/mapping";
import { system_generated } from "@/views/Module/Other/fields/system_generate";
import { module_details_by_id } from "@/views/Module/Other/module_details";
import { defineStore } from "pinia";
import { useDropdownStore } from "./dropdown";
interface formState{
    [index:string] : any
}
export const useUserStore = defineStore('user',{
    state:()=>{
        return{
            modal:false,
            loading:false,
            id:"",
            module:"",
            form:{} as formState,
            errors:[] as formState
        }
    },
    actions:{
        async save(){
            try {
                this.loading = true;
                let response;
                if(this.id == ""){
                     response = await axiosIns.post("users",this.form);
                }
                else{
                     response = await axiosIns.put("users/"+this.id,this.form);
                }
                router.push('/view/users/'+response.data.data);
                this.loading = false;
            } catch (error:any) {
                const response = error.response;
                if(response.status == 422){
                    const errors = Object.keys(response.data.errors);
                    errors.map(err=>{
                        this.errors[err] = response.data.errors[err][0];
                    })
                }
                this.loading = false;
            }
        },
        async edit_profile(){
            try {
                this.loading = true;
                const response = await axiosIns.get("users/"+this.id);
                const data = response.data.data;
                this.form.firstname = data.firstname;
                this.form.lastname = data.lastname;
                this.form.email = data.email;
                this.form.user_roles = data.user_roles.id;
                data.user_privileges.map((item:any)=>{
                    const module_details:any = module_details_by_id(item.module);
                    this.form[`user_privileges.${module_details.name}`] = !!item.status;
                })
                system_generated.map((item:any)=>{
                    this.form[item.name] = data[item.name];
                })
                this.loading = false;
            } catch (error) {
                
            }
        },
        async get_details(){
            try {
                this.loading = true;
                const response = await axiosIns.get("users/"+this.id);
                const data = response.data.data;
                this.form.firstname = data.firstname;
                this.form.lastname = data.lastname;
                this.form.email = data.email;
                this.form.user_roles = data.user_roles.label;
                // this.form.roles = data.roles.label;
                this.form.user_roles_edit = data.user_roles.id;
                data.user_privileges.map((item:any)=>{
                    const module_details:any = module_details_by_id(item.module);
                    this.form[`user_privileges.${module_details.name}`] = !!item.status;
                })
                system_generated.map((item:any)=>{
                    this.form[item.name] = data[item.name];
                })
                this.loading = false;
            } catch (error) {
                
            }
        },
        async edit(){
            try {
                const dropdown_store = useDropdownStore();
                this.loading = true;
                const response = await axiosIns.get("users/edit/"+this.id);
                const data = response.data.data;
                this.form.firstname = data.firstname;
                this.form.lastname = data.lastname;
                this.form.email = data.email;
                this.form.user_roles = data.user_roles.id;
                // this.form.roles = data.roles.id;
                data.user_privileges.map((item:any)=>{
                    const module_details:any = module_details_by_id(item.module);
                    this.form[`user_privileges.${module_details.name}`] = !!item.status;
                })
                // dropdown_store.set_dropdownlist['roles'] =  dropdown_store.dropdownlist['roles'].filter((option:any) => option.parent_id == data.user_roles.id );
                this.loading = false;
            } catch (error) {
                
            }
        }
    },
    persist:true
})