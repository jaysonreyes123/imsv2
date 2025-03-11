import router from "@/router";
import { useDropdownStore } from "@/stores/dropdown";

interface formState{
    [index:string] : any;
}
const setForm = (blocks:any) =>{
    let form:formState = {};
    let required:formState = [];
    blocks.map((block:any) => {
        block.fields.map((field:any)=>{
            if(field.required){
                required.push({label:field.label,field:field.name})
            }
            if(field.default_value){
                form[field.name] = field.default_value;
            }
            else{
                form[field.name] = "";
            }
        })
    })
    return {form,required}
}


const get_fields = (blocks:any) =>{
    let fields:formState = [];
    blocks.map((block:any) => {
        block.fields.map((field:any)=>{
                fields.push(field);
        })
    })
    return fields;
}

const setFormWithData = (blocks:any,data:any,type:string = "save") =>{
    let formdata:formState = {};
    const dropdown_store = useDropdownStore();
    // formdata["id"] = router.currentRoute.value.params.id;
    // formdata["module"] = router.currentRoute.value.params.module;
    blocks.map((block:any) => {
        block.fields.map((field:any)=>{
            if(field.type == 'multiselect' && type == 'save'){
                formdata[field.name] = JSON.parse(data[field.name]);
            }
            else if(field.type == 'number'){
                formdata[field.name] = parseInt(data[field.name]);
            }
            else if(field.type == 'dropdown' && type == 'save'){
                if(field.option == "set"){
                    const set_dropdown_value = dropdown_store.dropdownlist[field.target].filter((option:any) => option.parent_id == data[field.name]);
                    dropdown_store.set_dropdownlist[field.target] = set_dropdown_value;
                }
                formdata[field.name] = !isNaN(parseInt(data[field.name])) ? parseInt(data[field.name]) : "" ;
            }
            else{
                formdata[field.name] = data[field.name];
            }
        })
    })
    return formdata
}
export {setForm,setFormWithData,get_fields};