<template lang="">
    <div>
        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
                        Personal Information
                    </h4>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                        <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">First Name</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{  auth_store.user_details.firstname }}</p>
                        </div>

                        <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Last Name</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ auth_store.user_details.lastname  }}</p>
                        </div>

                        <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                            Email address
                        </p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth_store.user_details.email }}
                        </p>
                        </div>

                        <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Role</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ auth_store.user_details.user_roles.label }}</p>
                        </div>

                        <!-- <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Bio</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">Team Manager</p>
                        </div> -->
                        </div>
                    </div>
                    <div class="gap-4 flex">
                        <Button @click="edit" text="Edit Personal Information" btnClass="btn btn-sm btn-outline-primary" icon="heroicons:pencil-square" />
                        <Button @click="changepassword" text="Change Password" btnClass="btn btn-sm btn-outline-primary" icon="heroicons:pencil-square" />
                    </div>
            </div>
        </div>
    </div>
    <Modal 
        title="Edit Personal Information"
        sizeClass="max-w-3xl"
        :activeModal="modal"
        @close="closeModal">
        <Loading v-model:active="user_store.loading"/>
        <div>
            <div>
                <div class="fromGroup relative mb-4">
                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">First Name <span class="text-red-500">*</span></label>
                    <Textinput  

                        :classInput="user_store.errors.firstname ? '!border !border-red-500':''" 
                        v-model="user_store.form.firstname"
                        :placeholder="`Enter First Name`" />
                        <label class="validation-label" v-if="user_store.errors.firstname !=''" >{{user_store.errors.firstname}}</label>
                </div>
                <div class="fromGroup relative mb-4">
                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">Last Name <span class="text-red-500">*</span></label>
                    <Textinput  

                        :classInput="user_store.errors.lastname ? '!border !border-red-500':''" 
                        v-model="user_store.form.lastname"
                        :placeholder="`Enter First Name`" />
                        <label class="validation-label" v-if="user_store.errors.lastname !=''" >{{user_store.errors.lastname}}</label>
                </div>
                <div class="fromGroup relative mb-4">
                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">Email <span class="text-red-500">*</span></label>
                    <Textinput  
                        v-model="user_store.form.email"
                        :isReadonly="true" 
                        :placeholder="`Enter Email`" />
                </div>

                <div class="fromGroup relative mb-4" v-if="auth_store.user_details.user_roles.id == 1">
                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">Role <span class="text-red-500">*</span></label>
                    <v-select 
                        :loading="dropdown_store.dropdownloading['user_roles']" 
                        placeholder="Select an option"  
                        :reduce="(option) => option.id" :options="dropdown_store.dropdownlist['user_roles']"
                        v-model="user_store.form['user_roles']"/>   
                </div>
            </div>
        </div>
        <template #footer>
            <Button @click="save_profile" text="Save changes" />
        </template>
    </Modal>
    <Modal 
        title="Change Password"
        sizeClass="max-w-3xl"
        :activeModal="modal_changepass"
        @close="closeModal">
        <Loading v-model:active="user_store.loading"/>
        <div>
            <div>
                <div class="fromGroup relative mb-4">
                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">New Password <span class="text-red-500">*</span></label>
                    <Textinput  
                        :classInput="user_store.errors.new_password ? '!border !border-red-500':''" 
                        v-model="new_password"
                        type="password"
                        :placeholder="`Enter First Name`" />
                        <label class="validation-label" v-if="user_store.errors.new_password !=''" >{{user_store.errors.new_password}}</label>
                </div>
                <div class="fromGroup relative mb-4">
                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">Re-type Password</label>
                    <Textinput  
                        :classInput="user_store.errors.retype_password ? '!border !border-red-500':''" 
                        v-model="retype_password"
                        type="password"
                        :placeholder="`Enter First Name`" />
                        <label class="validation-label" v-if="user_store.errors.retype_password !=''" >{{user_store.errors.retype_password}}</label>
                </div>
            </div>
        </div>
        <template #footer>
            <Button @click="save_changepassword" text="Save changes" />
        </template>
    </Modal>
</template>
<script setup>
import Modal from "@/components/Modal/index.vue";
import Button from "@/components/Button/index.vue";
import Icon from "@/components/Icon/index.vue";
import { user_fields } from "../../Other/fields/users";
import { useAuthStore } from "@/stores/auth";
import { useUserStore } from "@/stores/user";
import Textinput from "@/components/Textinput/index.vue";
import { emailValidation } from "../../Save/validation";
import { onMounted, ref } from "vue";
import { useDropdownStore } from "@/stores/dropdown";
import { setForm } from "../../Other/fields/mapping";
import axiosIns from "@/library/axios";
const dropdown_store = useDropdownStore();
const auth_store = useAuthStore();
const user_store = useUserStore();
const modal = ref(false);
const modal_changepass = ref(false);
const fields = user_fields[0];
const new_password = ref("")
const retype_password = ref("");
const closeModal = () =>{
    user_store.errors = [];
    modal.value = false;
    new_password.value = "";
    retype_password.value = "";
    modal_changepass.value = false;
}
const edit = () =>{
    user_store.edit();
    modal.value = true;
}
const changepassword = () =>{
    modal_changepass.value = true;
}
const save_changepassword = () =>{
    let error = false;
    if(new_password.value == ""){
        error = true;
        user_store.errors = Object.assign(user_store.errors,{new_password:"Password is required"});
    }
    else{
        delete user_store.errors.new_password;
    }

    if(retype_password.value != new_password.value){
        error = true;
        user_store.errors = Object.assign(user_store.errors,{retype_password:"Password doesn't match"});
    }
    else{
        delete user_store.errors.retype_password;
    }


    if(!error){
        user_store.form.password = new_password.value;
        save('change-password');
    }

}
const save_profile = async () =>{
    const {required} = setForm([fields]);
    required.map(required => {
        if(user_store.form[required.field] == "" || user_store.form[required.field] === null){
            user_store.errors[required.field] = `${required.label} is required`;
        }
        else{
            delete user_store.errors[required.field];
        }
    })

    const errors = Object.keys(user_store.errors).filter(f => f != 'password');
    if(errors.length == 0){
        save('personal-information')
    };
}
async function save(action){
    try {
        user_store.loading = true;
        const response = await axiosIns.post("users/profile/edit/"+action+"/"+auth_store.user_details.id,user_store.form);
        auth_store.user_details = response.data.data;
        location.reload();
    } catch (error) {
        const response = error.response;
        if(response.status == 422){
            const errors = Object.keys(response.data.errors);
            errors.map(err=>{
                user_store.errors[err] = response.data.errors[err][0];
            })
        }
        user_store.loading = false;
    }
}
function emailvalidation(event,field,label){
    const {email,message} = emailValidation(label,event.target.value)
    
    if(email){
        delete user_store.errors[field];
    }
    else{
        user_store.errors[field] = message;
    }
    
}
onMounted(()=>{
    dropdown_store.get_dropdown('user_roles');
})
</script>