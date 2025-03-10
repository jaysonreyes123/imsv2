<template lang="">
    <div>
        <Loading v-model:active="report_store.loading"/>
        <table class="w-full">
            <tr>
                <td style="text-align: left;"><img src="@/assets/images/logo/BAYAN911.jpg" style="width: 200px;display:block"></td>
                <td style="text-align: left;">
                    <span style="font-weight: bold;font-size: 25px;"> Incident Management System </span>
                </td>            
            </tr>
        </table>
        <br>
        <hr/>
        <br>
        <p class="text-center font-bold capitalize text-2xl">{{report_store.form.report_name}}</p>
        <br>
        <Card bodyClass="p-0">
        <vue-good-table
            :columns="report_store.column"
            :rows="report_store.data"
            styleClass=" vgt-table bordered"
            :sort-options="{
            enabled: false,
            }"
        />
        </Card>
    </div>
</template>
<script>
import Card from "@/components/Card/index.vue";
import { useReportStore } from "@/stores/report";
const report_store = useReportStore();
export default {
    components:{
        Card
    },
    data(){
        return{
            report_store
        }
    },
    created(){
        report_store.id = this.$route.params.id;
    },
    mounted(){
        report_store.get();
        this.$watch(
            ()=>report_store.loading,
            (loading) => {
              if(loading == false){
                setTimeout(()=>{
                    window.print();
                },500)
              }
            }
        )
    }   
}
</script>
<style lang="">
    
</style>