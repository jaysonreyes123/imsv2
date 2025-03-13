<template>
    <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Profile</h3>
    <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
            <div class="flex flex-col items-center w-full gap-6 xl:flex-row">
                <div class="w-20 h-20 overflow-hidden border border-gray-200 rounded-full dark:border-gray-800">
                <img class="dark:hidden" :src="avatar" alt="user" />
                <img class="dark:block hidden" :src="avatarDark" alt="user" />
                </div>
                <div class="order-3 xl:order-2">
                <h4 class="mb-2 text-lg font-semibold text-center text-gray-800 dark:text-white/90 xl:text-left">
                    {{ auth_store.user_details.firstname }} {{ auth_store.user_details.lastname }}
                </h4>
                <div class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth_store.user_details.user_roles.label }}</p>
                    <!-- <div class="hidden h-3.5 w-px bg-gray-300 dark:bg-gray-700 xl:block"></div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Arizona, United States</p> -->
                </div>
            </div>
            </div>
        </div>
        </div>
    </template>
<script setup>
import avatar from "@/assets/images/avatar/user.png";
import avatarDark from "@/assets/images/avatar/user-dark.png";
import { useAuthStore } from "@/stores/auth";
import { useUserStore } from "@/stores/user";
const user_store = useUserStore();
const auth_store = useAuthStore();
</script>