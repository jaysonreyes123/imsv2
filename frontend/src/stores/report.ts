
import { defineStore } from "pinia"; 
import { useDropdownStore } from "./dropdown";
import axiosIns from "@/library/axios";
import router from "@/router";
const dropdown_store = useDropdownStore();
interface formState{
    [index:string] : any;
}
export const useReportStore = defineStore('report', {
    state:()=>{
        return{
            loading:false,
            id:"",
            column:[],
            data:[],
            details:[],
            selected_col:[] as formState,
            errors:{
                report_name:"",
                module:"",
                selected_columns:"",
                type:"",
                dataset:"",
                group_by:"",

            } as formState,
            form:{
                report_name:"",
                module:"",
                option:"",
                data_field:"",
                related_module:[],
                selected_columns:[],
                filter:[],
                chart:{
                    type:"",
                    dataset:[],
                    group_by:[]

                },

            } as formState,
        }
    },
    actions:{
        async get(limit:number = 0){
            try {
                this.loading = true;
                const response = await axiosIns.get("reports/"+this.id,{
                    params:{
                        limit:limit
                    }
                });
                const data = response.data.data;
                this.column = data.column;
                this.data = data.data;
                this.form.report_name = data.details.report_name;
                this.form.option = data.details.type;
                this.details = data.details;
                this.loading = false;
            } catch (error) {
                
            }
        },
        async save(){
            try {
                this.loading = true;
                let response;
                if(this.id == ""){
                    response = await axiosIns.post('reports',this.form);
                }
                else{
                    response = await axiosIns.put('reports/'+this.id,this.form);
                }
                router.push("/view/reports/"+response.data.data);
                this.loading = false;
            } catch (error) {
                
            }
        },
        async edit(){
            try {
                this.loading = true;
                const response = await axiosIns.get("reports/edit/"+this.id,this.form);
                const data = response.data.data;
                this.form.report_name = data.report_name;
                this.form.module = data.module;
                this.form.related_module = data.related_module;


                if(data.type == 'list'){
                    data.report_columns.map((item:any)=>{
                        this.form.selected_columns.push(
                            {
                                label:item.label,
                                name:item.column,
                                module:item.module
                            }
                        )
                        this.selected_col.push(item.column);
                    })
                }
                else{
                    console.log(this.form.chart.group_by)
                    this.form.chart.type = data.report_charts.chart;
                    this.form.chart.group_by = [];
                    this.form.chart.dataset = data.report_charts.dataset;
                }
                data.report_conditions.map((item:any)=>{
                    let value = item.value;
                    if(item.type == 'dropdown'){
                        value = parseInt(item.value)
                        dropdown_store.get_dropdown(item.column)
                    }
                    this.form.filter.push(
                        {
                            field:item.column,
                            operator:item.operator,
                            type:item.type,
                            value:value,
                            module:item.module
                        }
                    );
                })
                this.loading = false;
            } catch (error) {
                
            }
        },
        async report_export(type:string){
            try {   
                this.loading = true;
                const response = await axiosIns.get("reports/generate/export/"+type+"/"+this.id,{
                    responseType:'blob'
                });
                const href = URL.createObjectURL(response.data);
                const link = document.createElement('a');
                link.href = href;
                link.setAttribute('download',this.form.report_name+"."+type); //or any other extension
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(href);
                this.loading = false;
            } catch (error) {
                
            }
        } 
    },
    persist:true
})