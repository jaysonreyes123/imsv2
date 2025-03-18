<template lang="">
    <div>
      <Skeleton class="mt-4" v-if="system_store.loading" :count="5"/>
        <Card v-else title="Updates" class="mt-4">
        <div v-if="system_store.activitylogs.logs.length > 0">
          <ul class="relative ltr:pl-2 rtl:pr-2">
              <li
                v-for="(item,i) in system_store.activitylogs.logs"
                :key="i"
                class="mt-5 flex"
              >
              
              <div class="w-[250px] text-xs">{{ item.created_at }}</div>
              <div class="w-[100px] ltr:border-l-2 rtl:border-r-2 border-slate-100 dark:border-slate-700 pb-4 last:border-none ltr:pl-[22px] rtl:pr-[22px] relative before:absolute ltr:before:left-[-8px] rtl:before:-right-2 before:top-[0px] before:rounded-full before:w-4 before:h-4 before:bg-slate-900 dark:before:bg-slate-600 before:leading-[2px] before:content-[url('@/assets/images/all-img/ck.svg')]">
              
              </div>
                <div>
                  <h2
                    class="text-sm font-medium dark:text-slate-400-900 mb-1 text-slate-600"
                  >
                    <span class="font-bold text-sky-500 ">{{ item.whodid }}</span> - <span class="dark:text-slate-400">{{item.action}}</span>
                  </h2>
                  <div v-if="item.status == 2">
                        <p class="text-xs dark:text-slate-400"></p>
                        <div v-for="field in item.fields" :key="field.id" class="mb-2">
                          <p class="text-xs text-slate-600"><b>{{ get_label(field.label) }}</b> changed</p>
                          <p class="text-xs text-slate-600"><b>From</b>: {{ get_value(field.label,field.oldvalue) }}</p>
                          <p class="text-xs text-slate-600"><b>To</b>: {{ get_value(field.label,field.newvalue)  }}</p>
                        </div>
                  </div>
                  <div v-else-if="item.status == 4 || item.status == 5 ">
                        <p class="text-xs dark:text-slate-400"></p>
                        <div>
                          <p class="text-xs"><b>{{ item.fields.module }}</b> - {{ item.fields.entityname }}</p>
                        </div>
                  </div>
                </div>
              </li>
            </ul>
            <div class="w-full mt-4 flex justify-center" v-if="!system_store.activitylogs.is_last_page">
              <Button text="load more" @click="load_more" :isLoading="system_store.loading" btnClass="btn-sm btn-outline-white"/>
            </div>
        </div>
        <div v-else>
          <div class="p-4 mt-4 border border-slate-400 rounded text-center">
            <span>No Updates</span>
          </div>
        </div>
      </Card>
    </div>
</template>
<script>
import { GetFields } from "../../Other/fields";
import Button from "@/components/Button/index.vue";
import Card from "@/components/Card/index.vue";
import { useSystemStore } from '@/stores/system';
import { get_fields } from "../../Other/fields/mapping";
import { useDropdownStore } from "@/stores/dropdown";
const system_store = useSystemStore();
const dropdown_store = useDropdownStore();
export default {
    components:{
        Card,Button
    },
    data(){
        return{
            system_store
        }
    },
    beforeMount(){
      system_store.activitylogs.loading = false;
      system_store.activitylogs.is_last_page = false;
      system_store.activitylogs.current_page = 1;  
      system_store.activitylogs.logs = [];
      system_store.form.id = this.$route.params.id;
      system_store.form.module = this.$route.params.module;
    },
    mounted(){
        system_store.activitylogs.current_page = 1;
        system_store.activity_logs();
    },
    computed:{
      module_fields(){
          const fields =  GetFields(this.$route.params.module);
          return get_fields(fields);
      }
    },
    methods:{
      get_label(field){
        const find_field = this.module_fields.find(f => f.name == field);
        return find_field.label
      },
       get_dropdown(field,value){
        let label = "";
        if(dropdown_store.dropdownlist[field] === undefined){
           dropdown_store.get_local_dropdown(field);
        }
        else{
          const find_value = dropdown_store.dropdownlist[field].find(f => f.id == value);
          if(find_value){
            label = find_value.label;
          }
        }
        return label;
      },

      multiselect(field,value){
        let label = [];
        if(dropdown_store.dropdownlist[field] === undefined){
           dropdown_store.get_local_dropdown(field);
        }
        else{
          const id = JSON.parse(value);
          if(id.length > 0){
            id.map((item)=>{
              const find_value = dropdown_store.dropdownlist[field].find(f => f.id == item);
              if(find_value){
                label.push(find_value.label);
              }
            })
          }
        }
        return label.join(",");
      },
      
      get_value(field,value){
        const find_field = this.module_fields.find(f => f.name == field);
        if(find_field.type == 'dropdown'){
          return this.get_dropdown(field,value);
        }
        else if(find_field.type == 'multiselect'){
          return this.multiselect(field,value);
        }
        else{
            return value;
        }
      },
      scroll_paginate(){
        const current_scrollheight = window.scrollY + document.body.clientHeight;
        const total_scrollheight = document.body.scrollHeight;
        if(current_scrollheight == total_scrollheight){
            if(system_store.current_page < system_store.last_page){
              system_store.activitylogs.current_page++;
              system_store.activity_logs();
            }
        }
      },
      load_more(){
          system_store.activitylogs.current_page++;
          system_store.activity_logs();
        }
    }
}
</script>
<style lang="">
    
</style>