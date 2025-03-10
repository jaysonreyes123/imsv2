import router from "@/router";

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
    // formdata["id"] = router.currentRoute.value.params.id;
    // formdata["module"] = router.currentRoute.value.params.module;
    blocks.map((block:any) => {
        block.fields.map((field:any)=>{
            if(field.type == 'multiselect' && type == 'save'){
                formdata[field.name] = JSON.parse(data[field.name]);
            }
            else{
                formdata[field.name] = data[field.name];
            }
        })
    })
    return formdata
}
export {setForm,setFormWithData,get_fields};