<template lang="">
    <div>
        <div class="text-base text-slate-600 dark:text-slate-300">
            <div v-for="(item,i) in list_store.form.filter" :key="i" 
            class="lg:grid-cols-2 md:grid-cols-2 grid-cols-1 grid gap-5 mb-5 last:mb-0">
                <v-select 
                    placeholder="Select an option"
                    @option:selected="field_selected($event,i)"
                    :options="fields" 
                    :reduce="option => option.name" 
                    v-model="item.field" 
                />
                <div class="flex justify-between items-end space-x-5">
                    <div class="flex-1">
                        <Textinput v-if="
                            item.type == 'text' || 
                            item.type == 'number' || 
                            item.type == 'generate' ||
                            item.type == 'coordinates' ||
                            item.type == 'email' ||
                            item.type == 'textarea' || 
                            item.type == 'hidden'
                            " 
                            placeholder=""
                            class="flex-1" 
                            v-model="list_store.form.filter[i].value"
                        />
                        <v-select v-else-if="list_store.form.filter[i].type == 'dropdown'" :options="dropdown_store.dropdownlist[item.field]" :reduce="(option) => option.id" v-model="item.value"
                                placeholder="Select an option" class="p-1" 
                            />
                        <flat-pickr v-else-if="item.type == 'time'" class="form-control" placeholder="HH:mm"
                            :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i:00', time_24hr: true, minuteIncrement: 1 }" v-model="list_store.form.filter[i].value" />
                        <flat-pickr v-else-if="item.type == 'date' "  class="form-control"
                            placeholder="yyyy-mm-dd" v-model="list_store.form.filter[i].value" :config="{ dateFormat: 'Y-m-d' }" />
                    </div>
                    <div class="flex-none relative w-14">
                        <button type="button"
                            class="inline-flex items-center justify-center h-10 w-10 bg-danger-500 text-lg border rounded border-danger-500 text-white"
                            @click="removeCondition(i)">
                            <Icon icon="heroicons-outline:trash" />
                        </button>
                    </div>
                </div>
            </div>
            <div class=" md:flex justify-end md:mr-20">
                <Button 
                    icon="heroicons-outline:plus" 
                    text="Add Condition"
                    btnClass="btn-outline-dark btn-sm w-full md:w-[140px]"
                    @click="addCondition"
                />
            </div>
        </div>
    </div>
</template>
<script>
import Textinput from "@/components/Textinput/index.vue";
import Textarea from "@/components/Textarea/index.vue";
import Icon from "@/components/Icon/index.vue";
import Button from "@/components/Button/index.vue"
import { useListStore } from '@/stores/list';
import { useDropdownStore } from "@/stores/dropdown";
import { GetFields } from "../../Other/fields";
import { get_fields } from "../../Other/fields/mapping";
const list_store = useListStore();
const dropdown_store = useDropdownStore();

export default {
    components:{
        Icon,
        Button,
        Textinput,
        Textarea
    },
    data(){
        return{
            list_store,
            dropdown_store,
        }
    },
    beforeMount(){
        list_store.form.filter= [
            {
                field:"",
                value:"",
                type:"text"
            }
        ];
        list_store.module = this.$route.params.module;

    },
    computed:{
        fields(){
            const blocks = GetFields(list_store.module);
            const fields = get_fields(blocks);
            return fields;
        }
    },
    methods:{
        field_selected(event,position){
            list_store.form.filter[position].value = "";
            list_store.form.filter[position].type = event.type;
            if(event.type == 'dropdown') {
                dropdown_store.get_dropdown(event.name);
            }
        },
        removeCondition(index){
            list_store.form.filter.splice(index,1)
        },
        addCondition(){
            if(list_store.form.filter.length < 3){
                list_store.form.filter.push(
                    {
                        field:"",
                        value:"",
                        type:"text"
                    }
                );
            }
        }
    }
}
</script>