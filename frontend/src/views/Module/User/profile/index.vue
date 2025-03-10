<template>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
      <userInfo/>
      <PersonalInfo/>
      <userPrivileges/>
    </div>
</template>
<script setup>
import { useUserStore } from "@/stores/user";
import PersonalInfo from "./personal-info.vue";
import userInfo from "./user-info.vue";
import userPrivileges from "./user-privileges.vue";
import { useAuthStore } from "@/stores/auth";
const auth_store = useAuthStore();
const user_store = useUserStore();
user_store.id = auth_store.user_details.id;
user_store.get_details();
user_store.errors = [];
</script>
<style>
.validation-label{
    @apply text-danger-500 block text-sm mt-2
}
</style>