<template lang="">
    <div>
        <Loading v-model:active="module_store.loading"/>
        <table class="w-full">
            <tr>
                <td style="text-align: left;"><img src="@/assets/images/logo/BAYAN911.jpg" style="width: 200px;display:block"></td>
                <td style="text-align: left;">
                    <span style="font-weight: bold;font-size: 25px;"> Incident Management System </span>
                </td>            
            </tr>
        </table>
        <br>
        <div style="border-top: 3px solid #032e61;"></div>
        <h2  style="text-align:center; font-weight:bold; font-size: 20px; margin-bottom: 10px; margin-top: 10px;">INCIDENT REPORT</h2>
        <div>
            <table style="width: 100%;" class="reportdata">
                <tr>
                    <td><b>INCIDENT NO:</b></td>                
                    <td>{{ module_store.form.incident_no }}</td>
                </tr>
                <tr>
                    <td><b>INCIDENT TYPE:</b></td>                
                    <td>{{ get_dropdown_label(module_store.form.incident_types) }}</td>
                </tr>
                <tr>
                    <td><b>LOCATION:</b></td>                
                    <td>{{ module_store.form.street_name }}</td>
                </tr>
                <tr>
                    <td><b>NEAREST LANDMARK:</b></td>                
                    <td>{{ module_store.form.nearest_landmark }}</td>
                </tr>
                <tr>
                    <td><b>CONTACT NO:</b></td>                
                    <td>{{ module_store.form.caller_contact }} </td>
                </tr>
                <tr>
                    <td><b>DATE AND TIME:</b></td>                
                    <td>{{ module_store.form.date_of_incident }} {{ module_store.form.time_of_incident }} </td>
                </tr>
                <tr>
                    <td><b>RELAYED TO:</b></td>                
                    <td>{{ module_store.form.response_team }}</td>
                </tr>
                <tr>
                    <td><b>STATUS:</b></td>                
                    <td>{{ get_dropdown_label(module_store.form.incident_statuses) }}</td>
                </tr>
            </table>
            <br>
		    <br>
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left;">PREPARED BY:</td>
                    <td style="text-align: right;">DATE PRINTED: {{current_datetime}}</td>				
                </tr>
		    </table>
        </div>
    </div>
</template>
<script>
import header_logo from "@/assets/images/logo/navy.png";
import { useModuleStore } from "@/stores/module";
const module_store = useModuleStore();
export default {
    data(){
        return{
            header_logo,
            module_store,
        }
    },
    mounted(){
        setTimeout(()=>{
            window.print();
        },500)
    },
    methods:{
        get_dropdown_label(data){
            let label = "";
     
            if(data != "" && data != null){
                if(label !== undefined){
                    label = data.label;
                }
            }
            return label;
        }
    }
}
</script>
<style>
.reportdata {
            border-collapse: collapse;
            width: 100%;
            padding-top: 10px;
        }

        .reportdata td, .reportdata th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .reportdata th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #032e61;
            color: black;
        }
</style>