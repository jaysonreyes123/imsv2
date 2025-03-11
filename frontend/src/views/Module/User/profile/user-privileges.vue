<template lang="">
    <div>
        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
                        User Privileges
                    </h4>
                    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 lg:gap-7 2xl:gap-x-32">
                        <div v-for="(item,index) in auth_store.user_details.user_privileges">
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">{{module_label(item.module)}}</p>
                            <span 
                                v-if="item.status"
                                class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Active</span>
                            <span v-else class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">Inactive</span>
                        </div>
                    </div>
                </div>
                <Button @click="edit" text="Edit" btnClass="btn btn-sm btn-outline-primary" icon="heroicons:pencil-square" />
            </div>
        </div>
    </div>
    <Modal 
        title="Edit User Privileges"
        sizeClass="max-w-3xl"
        :activeModal="modal"
        @close="closeModal">
        <Loading v-model:active="user_store.loading"/>
        <div class="grid grid-cols-3">
            <div v-for="(field,i) in fields.fields.filter(f => f.name != 'password')">
                <div class="fromGroup relative mb-6" v-if="field.type == 'checkbox' ">
                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">{{ field.label }} <span class="text-red-500" v-if="field.required">*</span></label>
                    <Switch v-if="user_store.form[field.name] == 1" v-model="user_store.form[field.name]" :active="true" class="mb-5" />
                    <Switch v-if="user_store.form[field.name] == 0" v-model="user_store.form[field.name]" :active="false" class="mb-5" />
                </div>
            </div>
        </div>
        <template #footer>
            <Button @click="save_profile" text="Save changes" />
        </template>
    </Modal>
</template>
<script setup>
import Switch from "@/components/Switch/index.vue";
import Modal from "@/components/Modal/index.vue";
import Button from "@/components/Button/index.vue";
import Icon from "@/components/Icon/index.vue";
import {useModuleStore} from "@/stores/module";
import { useAuthStore } from "@/stores/auth";
import { module_details_by_id } from "../../Other/module_details";
import { useUserStore } from "@/stores/user";
import { ref } from "vue";
import { user_fields } from "../../Other/fields/users";
import axiosIns from "@/library/axios";
const auth_store = useAuthStore();
const module_store = useModuleStore();
const user_store = useUserStore();
const fields = user_fields[1];
const module_label = (module)=>{
    const module_detail =  module_details_by_id(module);
    let label = "";
    if(module_detail){
        label = module_detail.label;
    }
    return label;
}
const modal = ref(false);

const closeModal = () =>{
    modal.value = false;
}
const edit = () =>{
    modal.value = true;
}
const save_profile = async () =>{
    save('user-privileges')
}
async function save(action){
    try {
        user_store.loading = true;
        const response = await axiosIns.post("users/profile/edit/"+action+"/"+auth_store.user_details.id,user_store.form);
        auth_store.user_details = response.data.data;
        location.reload();
    } catch (error) {
        user_store.loading = false;
    }
}
</script>