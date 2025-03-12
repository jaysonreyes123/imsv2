<template lang="">
    <div>
        <Breadcrum :title="module_detail.label" :subtitle="this.$route.params.module =='reports' ? report_store.form.option : entityname ">
            <template #button>
                <div class="flex gap-x-2">
                    <div v-if="this.$route.params.module != 'reports'">
                        <router-link  :to="`/save/${this.$route.params.module}/${this.$route.params.id}`">
                            <Button 
                                icon="heroicons-outline:pencil-square" 
                                btnClass="btn-primary" 
                                text="Edit" 
                            />
                        </router-link>
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
        <Card v-if="this.$route.params.module != 'reports' ">
            <TabList class="lg:space-x-4 md:space-x-3 space-x-0 rtl:space-x-reverse">
            <Tab 
                v-slot="{ selected }"
                as="template"
                v-for="(item, i) in menu"
                :key="i"
            >
                <span>
                <Button :btnClass="selected ? 'btn btn-primary px-8' : 'text-dark' " :text="item.label" />
                </span>
            </Tab>
            </TabList>
        </Card>
        <TabPanels>
            <TabPanel v-for="(item,i) in menu" :key="item">
                <div v-if="item.related">
                    <Comment v-if="item.name == 'comments'" />
                    <Related v-else :related_module="item.name" :action_button="item.action" />
                </div>
                <div v-else>
                    <div v-if="item.name == 'detail' ">
                        <component v-if="this.$route.params.module=='users'" is="userDetail"></component>
                        <component v-else-if="this.$route.params.module=='reports'" is="reportDetail"></component>
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
import reportDetail from "@/views/Module/Report/detail.vue";
import userDetail from "../User/detail.vue";
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
import {module_details} from "../Other/module_details";
import Summary from "./summary/index.vue";
import { ref } from "vue";
import { useReportStore } from "@/stores/report";
import { useRelatedStore } from "@/stores/related";
import { useModuleStore } from "@/stores/module";
const selectedTab = ref(0);
const report_store = useReportStore();
const related_store = useRelatedStore();
const module_store = useModuleStore();
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
        detail,update,Related,Comment,Header,userDetail,reportDetail,Summary,
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
            parse.map(item=>{
                entityname_.push(module_store.form[item])
            })
            return entityname_.join(" ");
        }
    },
    data(){
        return{
            selectedTab,report_store
        }
    },
    created(){
        related_store.modal = false;
        selectedTab.value = 0;
    },
    methods:{
        changeTab(index){
            selectedTab.value = index;
        },
        report_export(type){
            report_store.report_export(type);
        }
    }
}
</script>
<style lang="">
    
</style>