import axiosIns from "@/library/axios";
import { GetColumn } from "@/views/Module/Other/columns";
import { defineStore } from "pinia";
interface formState{
    [index:string] : any;
}
interface DeleteFrom{
    module:string,
    id:any
}
interface columnSate{
    [index:string] : any 
}
export const useListStore = defineStore('list',{
    state:()=>{
        return{
            loading:false,
            modal:false,
            module:"",
            data:[],
            fields:[],
            selected_row:[],
            page:{
                current_page:1,
                per_page:15,
                total_page:1
            },
            form:{
                search:"",
                search_field:[],
                filter:[]
            }
        }
    },
    getters:{
        columns(state){
            return GetColumn(state.module);
        }
    },
    actions:{
        async load(){
            try {
                this.loading = true;
                const response = await axiosIns.get("module?page="+this.page.current_page,{
                    params:{
                        module:this.module,
                        filter:this.form.filter,
                        search:{
                            value:this.form.search,
                            search_field:this.form.search_field
                        }
                    }
                });
                const load_data = response.data.data;
                this.data = load_data.data;
                this.page.current_page = load_data.current_page;
                this.page.per_page = load_data.per_page;
                this.page.total_page = load_data.total;
                this.loading = false;
            } catch (error) {
                
            }
        },
        async delete(id:any){
            try {
                this.loading = true;
                let id_delete:number[] = [];
                if(typeof id == 'number'){
                    id_delete.push(id);
                }
                else{
                    id_delete = [...id];
                }
                const delete_form:DeleteFrom = {
                    module:this.module,
                    id:id_delete
                }
                const response = await axiosIns.post("module/delete",delete_form);
                this.load();
            } catch (error) {
                
            }
        },
        async save_selected_row(form:object){
            try {
                this.loading = true;
                const response = await axiosIns.post("related/save_selected_row",form);
                this.load();

            } catch (error) {
                
            }
        },
    },
    persist:true
})