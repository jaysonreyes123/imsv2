<template lang="">
    <div>
        <Breadcrum :title="module_detail.label" :subtitle="this.$route.params.module =='reports' ? report_store.form.option : entityname ">
            <template #button>
                <div class="flex gap-x-2">
                    <div v-if="this.$route.params.module != 'reports' && this.$route.params.module != 'insight_reports'   ">
                        <router-link  :to="`/save/${this.$route.params.module}/${this.$route.params.id}`">
                            <Button 
                                icon="heroicons-outline:pencil-square" 
                                btnClass="btn-primary" 
                                text="Edit" 
                            />
                        </router-link>
                    </div>
                    <div v-else>
                        <div v-if="this.$route.params.module == 'insight_reports' ">
                            <Button 
                                    icon="heroicons:document-arrow-down" 
                                    btnClass="btn-primary" 
                                    text="PDF" 
                                    @click="insight_report_generate()"
                                />
                        </div>
                        <div v-else>
                            <div v-if="report_store.form.option == 'list'">
                            <div class="flex gap-4">
                                <router-link target="_blank" :to="{name:'print-report',params:{id:this.$route.params.id}}">
                                    <Button
                                        icon="heroicons-outline:pencil-square"
                                        text="Print"
                                        btnClass="btn-primary shadow-base2"
                                        iconPosition="left"
                                    />
                                </router-link>
                                <Button 
                                    icon="heroicons:document-arrow-down" 
                                    btnClass="btn-success" 
                                    text="Csv" 
                                    @click="report_export('csv')"
                                />
                                <Button 
                                    icon="heroicons:document-arrow-down" 
                                    btnClass="btn-success" 
                                    text="Excel"
                                    @click="report_export('xlsx')" 
                                />
                            </div>
                        </div>
                        </div>
                    </div>
                    <div v-if="this.$route.params.module =='incidents'">
                        <router-link target="_blank" :to="`/print/${this.$route.params.module}/${this.$route.params.id}`">
                        <Button 
                            icon="heroicons-outline:printer"
                            btnClass="btn-outline-primary" 
                            text="Print" 
                        />
                        </router-link>
                    </div>
                </div>
            </template>
        </Breadcrum>
        <Header v-if="this.$route.params.module =='incidents' " />
        <TabGroup :selectedIndex="selectedTab" @change="changeTab">
        <Card v-if="this.$route.params.module != 'reports' && this.$route.params.module != 'insight_reports' ">
            <TabList>
            <Tab 
                v-slot="{ selected }"
                as="template"
                v-for="(item, i) in menu"
                :key="i"
            >
                <span>
                <Button :btnClass="selected ? 'btn btn-primary px-4 py-2' : 'text-dark px-3' " :text="item.label" />
                </span>
            </Tab>
            </TabList>
        </Card>
        <TabPanels>
            <TabPanel v-for="(item,i) in menu" :key="item">
                <div v-if="item.related">
                    <Comment v-if="item.name == 'comments'" />
                    <Transcript v-else-if="item.name == 'transcripts'"/>
                    <Related v-else :related_module="item.name" :action_button="item.action" />
                </div>
                <div v-else>
                    <div v-if="item.name == 'detail' ">
                        <component v-if="this.$route.params.module=='users'" is="userDetail"></component>
                        <component v-if="this.$route.params.module=='incidents'" is="incidentDetail"></component>
                        <component v-else-if="this.$route.params.module=='reports'" is="reportDetail"></component>
                        <component v-else-if="this.$route.params.module=='insight_reports'" is="InsightReport"></component>
                        <component  v-else :is="item.name" ></component>
                    </div>
                    <div v-else>
                    <component :is="item.name" ></component>
                    </div>
                </div>
            </TabPanel>
        </TabPanels>
        </TabGroup>
    </div>
</template>
<script>
import html2pdf from "html2pdf.js";
import reportDetail from "@/views/Module/Report/detail.vue";
import InsightReport from "@/views/Module/InsightReport/index.vue";
import userDetail from "../User/detail.vue";
import incidentDetail from "./components/incident_detail.vue";
import Header from "./components/Header.vue";
import Breadcrum from "../Other/Breadcrum.vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import Card from "@/components/Card/index.vue";
import Button from "@/components/Button/index.vue";
import related_menu from '../Other/related_menu';
import detail from "./components/Detail.vue";
import update from "./components/Update.vue";
import Related from "@/views/Module/Related/index.vue";
import Comment from "@/views/Module/Comment/index.vue";
import Transcript from "@/views/Module/Transcript/index.vue";
import {module_details} from "../Other/module_details";
import Summary from "./summary/index.vue";
import { ref } from "vue";
import { useReportStore } from "@/stores/report";
import { useRelatedStore } from "@/stores/related";
import { useModuleStore } from "@/stores/module";
import { useInsightReportStore } from "@/stores/insightreport";
const selectedTab = ref(0);
const report_store = useReportStore();
const related_store = useRelatedStore();
const module_store = useModuleStore();
const insight_report_store = useInsightReportStore();
const buttons = [
    {
      label: "Detail",
      name:"detail",
      related:false,
    },
    {
      label: "Update",
      name:"update",
      related:false,
    },
  ];
export default {
    components:{
        detail,update,Related,Comment,Header,userDetail,reportDetail,Summary,InsightReport,Transcript,incidentDetail,
        TabGroup, TabList, Tab, TabPanels, TabPanel,
        Button,
        Card,Breadcrum
    },
    computed:{
        menu(){
            let menu_button = [];
            if(related_menu(this.$route.params.module) === undefined){
                menu_button = buttons
            }
            else{
                menu_button = [...buttons,...related_menu(this.$route.params.module)];
            }
            if(this.$route.params.module == 'incidents'){
                const summary_menu = [{label:"Summary",name:"Summary",related:false}];
                menu_button = [...summary_menu,...menu_button];
            }
            return menu_button;
        },
        module_detail(){
            return module_details(this.$route.params.module);
        },
        entityname(){
            const parse = this.module_detail.entityname.split(",");
            let entityname_ = [];
            if(this.$route.params.module == 'insight_reports'){
                entityname_.push(insight_report_store.data.name);
            }
            else{
                parse.map(item=>{
                if(typeof module_store.form[item] == 'object' && module_store.form[item] !== null ){
                    entityname_.push(module_store.form[item].label)
                }
                else{
                    entityname_.push(module_store.form[item])
                }

            })
            }
            return entityname_.join(" ");
        }
    },
    data(){
        return{
            selectedTab,report_store,insight_report_store
        }
    },
    created(){
        related_store.modal = false;
        selectedTab.value = 0;
        this.$watch(
            ()=>this.$route.params.module,
            (module)=>{
                selectedTab.value = 0;
            }
        );
    },
    methods:{
        changeTab(index){
            selectedTab.value = index;
        },
        report_export(type){
            report_store.report_export(type);
        },
        insight_report_generate(){
            const element = document.querySelector("#insight-report-content");
            const options = {
                margin: 10,
                filename: insight_report_store.data.name+".pdf",
                image: { type: "jpeg", quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: "mm", format: "a4", orientation: "portrait" },
            };
            html2pdf().from(element).set(options).save();
        }
    }
}
</script>
<style lang="">
    
</style>