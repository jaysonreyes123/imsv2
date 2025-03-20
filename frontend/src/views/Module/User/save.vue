<template lang="">
    <div>
        <Loading v-model:active="user_store.loading"/>
        <form @submit.prevent="save">
            <Card v-for="(block,blockindex) in saveFields" :key="blockindex" :title="block.title" class="mt-6 shadow">
                <div class="lg:grid gap-x-12" style="grid-template-columns: 1fr 1fr;"> 
                    <div v-for="(field,i) in block.fields.filter(field => field.type != 'hidden')" :key="i" :class="`custom-grid-${i%2}`" class="mt-4">
                        <div class="fromGroup relative" v-if="field.type == 'checkbox' ">
                            <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{ field.label }} <span class="text-red-500" v-if="field.required">*</span></label>
                            <Switch v-if="user_store.form[field.name] == 1" v-model="user_store.form[field.name]" :active="true" class="mb-5" />
                            <Switch v-if="user_store.form[field.name] == 0" v-model="user_store.form[field.name]" :active="false" class="mb-5" />
                        </div>
                        <div class="fromGroup relative" v-else-if="field.type == 'checkbox' ">
                                <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{ field.label }} <span class="text-red-500" v-if="field.required">*</span></label>
                                <Switch v-if="user_store.form[field.name] == 1" v-model="user_store.form[field.name]" :active="true" class="mb-5" />
                                <Switch v-else-if="user_store.form[field.name] == 0" v-model="user_store.form[field.name]" :active="false" class="mb-5" />
                        </div>
                        <div class="fromGroup relative" v-else-if="field.type == 'dropdown' ">
                            <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{ field.label }} <span class="text-red-500" v-if="field.required">*</span></label>
                            
                            <v-select 
                                    v-if="field.option == 'set'"
                                    :loading="dropdown_store.dropdownloading[field.name]" 
                                    :class="user_store.errors[field.name] ? '!border !border-red-500':''" 
                                    :disabled="field.readonly == 1 ? true :false" 
                                    placeholder="Select an option"  
                                    @option:selected="set_dropdown_value($event,field.target)"
                                    :reduce="(option) => option.id" :options="dropdown_store.dropdownlist[field.name]"
                                    v-model="user_store.form[field.name]"
                                />  
                                <v-select 
                                    v-else-if="field.option == 'get'"
                                    :loading="dropdown_store.dropdownloading[field.name]" 
                                    :class="user_store.errors[field.name] ? '!border !border-red-500':''" 
                                    :disabled="field.readonly == 1 ? true :false" 
                                    placeholder="Select an option"  
                                    :reduce="(option) => option.id" :options="dropdown_store.set_dropdownlist[field.name]"
                                    v-model="user_store.form[field.name]"
                                />  
                                <v-select 
                                    v-else
                                    :loading="dropdown_store.dropdownloading[field.name]" 
                                    :class="user_store.errors[field.name] ? '!border !border-red-500':''" 
                                    :disabled="field.readonly == 1 ? true :false" 
                                    placeholder="Select an option"  
                                    :reduce="(option) => option.id" :options="dropdown_store.dropdownlist[field.name]"
                                    v-model="user_store.form[field.name]"
                                />   
                                <label class="validation-label" v-if="user_store.errors[field.name] !=''" >{{user_store.errors[field.name]}}</label>
                        </div>
                        <div v-else-if="field.type == 'email'">
                            <div class="fromGroup relative">
                                <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{field.label}} <span class="text-red-500" v-if="field.required">*</span></label>
                                <Textinput  
                                    :classInput="user_store.errors[field.name] ? '!border !border-red-500':''" 
                                    @input="emailvalidation($event,field.name,field.label)" 
                                    v-model="user_store.form[field.name]"
                                    :isReadonly="field.readonly == 1 ? true :false " 
                                    :placeholder="`Enter ${field.label}`" />
                                    <label class="validation-label" v-if="user_store.errors[field.name] !=''" >{{user_store.errors[field.name]}}</label>
                            </div>
                        </div>
                        <div v-else class="fromGroup relative">
                            <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{field.label}} <span class="text-red-500" v-if="field.required">*</span></label>
                            <Textinput 
                                type="text"
                                :classInput="user_store.errors[field.name] ? '!border !border-red-500':''" 
                                v-model="user_store.form[field.name]"
                                :placeholder="`Enter ${field.label}`" />
                            <label class="validation-label" v-if="user_store.errors[field.name] !=''" >{{user_store.errors[field.name]}}</label>
                        </div>
                    </div>
                </div>
            </Card>
            <Card class="mt-6 shadow">
                <Button type="submit" btnClass="btn btn-success w-full"  :text="`Save User`" />
            </Card>
        </form>
    </div>
</template>
<script>
import Switch from "@/components/Switch/index.vue";
import Card from "@/components/Card/index.vue";
import Button from "@/components/Button/index.vue";
import Textinput from "@/components/Textinput/index.vue";
import { GetFields } from '../Other/fields';
import { setForm } from '../Other/fields/mapping';
import { useUserStore } from '@/stores/user';
import { emailValidation } from "../Save/validation";
import { useDropdownStore } from "@/stores/dropdown";
const dropdown_store = useDropdownStore();
const user_store = useUserStore()
export default {
    components:{
        Card,Button,Textinput,Switch
    },
    data(){
        return{
            user_store,dropdown_store
        }
    },
    computed:{
        saveFields(){
            return GetFields('users')
        }
    },
    created(){
        user_store.method = this.method;
        dropdown_store.set_dropdown('users');
        const {form} = setForm(this.saveFields);
        user_store.errors = [];
        user_store.form = form;
        user_store.id = this.$route.params.id === undefined ? "" : this.$route.params.id;
        user_store.loading = false;
    },
    mounted(){
        if(user_store.id != ""){
            user_store.edit();
        }
    },
    methods:{
        emailvalidation(event,field,label){
            const {email,message} = emailValidation(label,event.target.value)
            
            if(email){
                delete user_store.errors[field];
            }
            else{
                user_store.errors[field] = message;
            }
            
        },
        set_dropdown_value(event,target){
           const id = event.id;
           user_store.form[target] = "";
           const set_dropdown_item =  dropdown_store.dropdownlist[target].filter(option => option.parent_id == id);
           dropdown_store.set_dropdownlist[target] = set_dropdown_item;
        },
        save(){
            const {required} = setForm(this.saveFields);
            const reqs = user_store.id != "" ? required.filter(req => req.field != 'password') : required;
            reqs.map(required => {
                if(user_store.form[required.field] == "" || user_store.form[required.field] === null){
                    user_store.errors[required.field] = `${required.label} is required`;
                }
                else{
                    delete user_store.errors[required.field];
                }
            })

            const errors = Object.keys(user_store.errors);
            console.log(errors)
            if(errors.length == 0){
                user_store.save();
            }
            else{
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }
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