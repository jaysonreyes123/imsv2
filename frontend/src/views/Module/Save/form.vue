<template lang="">
    <div>
        <Loading v-model:active="store.loading"/>
        <Card v-for="(block,blockindex) in saveFields" :key="blockindex" :title="block.title" class="mt-6 shadow">
                <div v-if="block.title == 'Location Details'">
                        <div>
                            <div class="mt-4">
                                <editMap :set_coordinates="store.form.coordinates"  @updateCoordinate="updateCoordinates"  />
                            </div>
                            <div class="lg:grid gap-x-12 mt-8" style="grid-template-columns: 1fr 1fr;"> 
                            <div v-for="(field,i) in block.fields" :key="i" class="mt-4">
                                <div class="fromGroup relative">
                                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{field.label}} <span class="text-red-500" v-if="field.required">*</span></label>
                                    <Textinput 
                                        :classInput="store.errors[field.name] ? '!border !border-red-500':''" 
                                        v-model="store.form[field.name]"
                                        :isReadonly="field.readonly == 1 ? true : false" 
                                        :placeholder="`Enter ${field.label}`" />
                                    <label class="validation-label" v-if="store.errors[field.name] !=''" >{{store.errors[field.name]}}</label>
                                </div>
                            </div>
                        </div>
                        </div>
                </div>
                <div v-else>
                    <div class="lg:grid gap-x-12" style="grid-template-columns: 1fr 1fr;"> 
                        <div v-for="(field,i) in block.fields.filter(field => field.type != 'hidden')" :key="i" :class="`custom-grid-${i%2}`" class="mt-4">
                            <div v-if="field.type == 'time' ">
                                <div class="fromGroup relative">
                                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{ field.label }} <span class="text-red-500" v-if="field.required">*</span> </label>
                                    <flat-pickr
                                    :class="store.errors[field.name] ? '!border !border-red-500':''" 
                                        class="form-control"
                                        placeholder="HH:mm:00"
                                        v-model="store.form[field.name]"
                                        :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i:00',time_24hr:true,minuteIncrement:1 }"
                                    />
                                    <label class="validation-label" v-if="store.errors[field.name] !=''" >{{store.errors[field.name]}}</label>
                                </div>
                            </div>
                            <div v-else-if="field.type == 'date' ">
                                <div class="fromGroup relative">
                                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{ field.label }} <span class="text-red-500" v-if="field.required">*</span></label>
                                    <flat-pickr
                                        class="form-control"
                                        :class="store.errors[field.name] ? '!border !border-red-500':''" 
                                        placeholder="yyyy-mm-dd"
                                        v-model="store.form[field.name]"
                                        :config="{ dateFormat:'Y-m-d' }"
                                    />
                                    <label class="validation-label" v-if="store.errors[field.name] !=''" >{{store.errors[field.name]}}</label>
                                </div>
                            </div>
                            <div v-else-if="field.type == 'phone'">
                                <div class="fromGroup relative">
                                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{field.label}} <span class="text-red-500" v-if="field.required">*</span></label>
                                    <Textinput  
                                        :classInput="store.errors[field.name] ? '!border !border-red-500':''" 
                                        @input="phonevalidation($event,field.name,field.label)" 
                                        @focusout="checknumber($event,field.name)"
                                        :isReadonly="field.readonly == 1 ? true :false " 
                                        v-model="store.form[field.name]"
                                        :placeholder="`Enter ${field.label}`" />
                                        <label class="validation-label" v-if="store.errors[field.name] !=''" >{{store.errors[field.name]}}</label>
                                </div>
                            </div>

                            <div v-else-if="field.type == 'email'">
                                <div class="fromGroup relative">
                                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{field.label}} <span class="text-red-500" v-if="field.required">*</span></label>
                                    <Textinput  
                                        :classInput="store.errors[field.name] ? '!border !border-red-500':''" 
                                        @input="emailvalidation($event,field.name,field.label)" 
                                        v-model="store.form[field.name]"
                                        :isReadonly="field.readonly == 1 ? true :false " 
                                        :placeholder="`Enter ${field.label}`" />
                                        <label class="validation-label" v-if="store.errors[field.name] !=''" >{{store.errors[field.name]}}</label>
                                </div>
                            </div>
                            
                            <div class="fromGroup relative" v-else-if="field.type == 'dropdown' ">
                                <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{ field.label }} <span class="text-red-500" v-if="field.required">*</span></label>
                               
                                <v-select 
                                    v-if="field.option == 'set'"
                                    :loading="dropdown_store.dropdownloading[field.name]" 
                                    :class="store.errors[field.name] ? '!border !border-red-500':''" 
                                    :disabled="field.readonly == 1 ? true :false" 
                                    placeholder="Select an option"  
                                    @option:selected="set_dropdown_value($event,field.target)"
                                    :reduce="(option) => option.id" :options="dropdown_store.dropdownlist[field.name]"
                                    v-model="store.form[field.name]"
                                />  
                                <v-select 
                                    v-else-if="field.option == 'get'"
                                    :loading="dropdown_store.dropdownloading[field.name]" 
                                    :class="store.errors[field.name] ? '!border !border-red-500':''" 
                                    :disabled="field.readonly == 1 ? true :false" 
                                    placeholder="Select an option"  
                                    :reduce="(option) => option.id" :options="dropdown_store.set_dropdownlist[field.name]"
                                    v-model="store.form[field.name]"
                                />  
                                <v-select 
                                    v-else
                                    :loading="dropdown_store.dropdownloading[field.name]" 
                                    :class="store.errors[field.name] ? '!border !border-red-500':''" 
                                    :disabled="field.readonly == 1 ? true :false" 
                                    :clearable="field.readonly == 1 ? false :true"
                                    placeholder="Select an option"  
                                    :reduce="(option) => option.id" :options="dropdown_store.dropdownlist[field.name]"
                                    v-model="store.form[field.name]">
                                    <template v-if="field.readonly" #open-indicator="{ attributes }">
                                        <span v-bind="attributes"></span>
                                    </template>
                                </v-select> 
                                    <label class="validation-label" v-if="store.errors[field.name] !=''" >{{store.errors[field.name]}}</label>
                            </div>

                            <div class="fromGroup relative" v-else-if="field.type == 'multiselect' ">
                                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{ field.label }} <span class="text-red-500" v-if="field.required">*</span></label>
                                    <v-select 
                                        :class="store.errors[field.name] ? '!border !border-red-500':''" 
                                        multiple
                                        :disabled="field.readonly == 1 ? true :false" 
                                        v-model="store.form[field.name]"
                                        :reduce="(option) => option.id" :options="dropdown_store.dropdownlist[field.name]"
                                        placeholder="Select an option"  
                                    />  
                                    <label class="validation-label" v-if="store.errors[field.name] !=''" >{{store.errors[field.name]}}</label>
                            </div>

                            <div v-else-if="field.type == 'number'">
                                <div class="fromGroup relative">
                                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{field.label}} <span class="text-red-500" v-if="field.required">*</span></label>
                                    <Textinput 
                                        type="number"
                                        step="1"
                                        min="1"
                                        :classInput="store.errors[field.name] ? '!border !border-red-500':''" 
                                        @input="numbervalidation($event,field.name,field.label)" 
                                        :isReadonly="field.readonly == 1 ? true :false " 
                                        v-model="store.form[field.name]"
                                        :placeholder="`Enter ${field.label}`" />
                                        <label class="validation-label" v-if="store.errors[field.name] !=''" >{{store.errors[field.name]}}</label>
                                </div>
                            </div>

                            <div class="fromGroup relative" v-else-if="field.type == 'checkbox' ">
                                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{ field.label }} <span class="text-red-500" v-if="field.required">*</span></label>
                                    <Switch v-if="store.form[field.name] == 1" v-model="store.form[field.name]" :active="true" class="mb-5" />
                                    <Switch v-if="store.form[field.name] == 0" v-model="store.form[field.name]" :active="false" class="mb-5" />
                            </div>

                            <div v-else-if="field.type == 'textarea'">
                                <div class="fromGroup relative">
                                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{ field.label }} <span class="text-red-500" v-if="field.required">*</span></label>
                                    <Textarea 
                                    :classInput="store.errors[field.name] ? '!border !border-red-500':''" 
                                        :isReadonly="field.readonly == 1 ? true :false" 
                                        v-model="store.form[field.name]"
                                        :placeholder="`Enter ${field.label}`" 
                                    />
                                    <label class="validation-label" v-if="store.errors[field.name] !=''" >{{store.errors[field.name]}}</label>
                                </div>
                            </div>
                            <div v-else class="fromGroup relative">
                                <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{field.label}} <span class="text-red-500" v-if="field.required">*</span></label>
                                <Textinput 
                                    type="text"
                                    :classInput="store.errors[field.name] ? '!border !border-red-500':''" 
                                    v-model="store.form[field.name]"
                                    :placeholder="`Enter ${field.label}`" />
                                <label class="validation-label" v-if="store.errors[field.name] !=''" >{{store.errors[field.name]}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </Card>
            <Card class="mt-6 shadow">
                <Button type="submit" btnClass="btn btn-success w-full"  :text="`Save ${store.moduleDetails.label}`" />
            </Card>
    </div>
</template>
<script>
import Switch from "@/components/Switch/index.vue";
import Card from "@/components/Card/index.vue";
import Button from "@/components/Button/index.vue";
import Textinput from "@/components/Textinput/index.vue";
import Textarea from "@/components/Textarea/index.vue";
import editMap from "../Map/edit-map.vue";
import { useDropdownStore } from "@/stores/dropdown";
import { phoneValidation,emailValidation,numberValidation } from "./validation";
const dropdown_store = useDropdownStore();
import Breadcrum from "../Other/Breadcrum.vue";
export default {
    components:{
        Card,Button,Textarea,Textinput,editMap,Switch,Breadcrum
    },
    props:['saveFields','store','module_details'],
    data(){
        return{
            dropdown_store
        }
    },
    created(){
        this.store.loading = false;
        const params = Object.keys(this.$route.query);
        if(params.length > 0){
            params.map(item => {
                this.store.form[item] = this.$route.query[item];
                if(item == 'caller_contact'){
                    this.checknumber(null,item)
                }
            })
        }
    },
    methods:{
        async checknumber($event,field){
            if(this.store.errors[field] === undefined && this.$route.params.module == 'incidents'){
                const data = await this.store.checknumber(this.store.form[field]); 
                this.store.form.contact_statuses = data;
            }
        },
        updateCoordinates(event){
            const {lng,lat} = event;
            this.store.form.coordinates = lng+","+lat;
        },
        phonevalidation(event,field,label){
            const {phone,message} = phoneValidation(label,event.target.value)
            this.store.form[field] = phone;
            this.store.errors[field] = message;
            if(message == ""){
                delete this.store.errors[field];
                console.log(this.store.errors[field]);
            }
        },
        numbervalidation(event,field,label){
            const value = numberValidation(event.target.value)
            this.store.form[field] = value;
        },
        emailvalidation(event,field,label){
            const {email,message} = emailValidation(label,event.target.value)
            this.store.errors[field] = message;
            if(email){
                delete this.store.errors[field];
            }
            
        },
        set_dropdown_value(event,target){
           const id = event.id;
           this.store.form[target] = "";
           const set_dropdown_item =  dropdown_store.dropdownlist[target].filter(option => option.parent_id == id);
           dropdown_store.set_dropdownlist[target] = set_dropdown_item;
        }
    }
}
</script>
<style>
.custom-grid-0{
      grid-column:1
  }
.custom-grid-1{
      grid-column:2
  }
.input-control[readonly],.input-group-control[readonly]{
   /* background-color: rgb(226 232 240) !important; */
    @apply dark:placeholder:text-white/30 dark:text-white/90
}
.validation-label{
    @apply text-danger-500 block text-sm mt-2
}
</style>