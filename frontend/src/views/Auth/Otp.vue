<template>
    <div class="flex flex-col flex-1 w-full lg:w-1/2">
        <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
          <div>
            <div class="mb-5 sm:mb-8">
              <h1
                class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md"
              >
              Two-Factor Authentication
              </h1>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Enter the 6-digit code sent to your email to verify your account
              </p>
            </div>
            <div>
              <Alert
                  v-if="auth_store.errors.status == 'error' "
                  class="mb-4"
                  variant="error"
                  title=""
                  :message="auth_store.errors.message"
                  :showLink="false"
                />
              <form @submit.prevent="auth_store.verify()">
                <div class="space-y-5">
                  <!-- Email -->
                  <div>
                    <label
                      for="email"
                      class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                    >
                      Enter your OTP here<span class="text-error-500">*</span>
                    </label>
                    <input
                      maxlength="6"
                      v-model="auth_store.otp.token"
                      type="text"
                      placeholder="Enter OTP"
                      class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                    />
                  </div>
                  <!-- Button -->
                  <div>
                    <Button text="Verify" type="submit" btnClass="btn btn-primary block w-full text-center" :isLoading="auth_store.loading" />
                  </div>
                  <!-- <p> Remembered your password? <router-link to="/auth/login" class="font-bold">Log in</router-link> instead.</p> -->
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
  </template>
  
  <script setup lang="ts">
  import Button from "@/components/Button/index.vue";
  import { useAuthStore } from "@/stores/auth.ts";
  import Alert from '@/components/ui/Alert.vue'
  const auth_store = useAuthStore();
  auth_store.otp.token = "";
  auth_store.errors.message = '';
  auth_store.errors.status = '';
  auth_store.form.email = '';
  auth_store.loading = false;
  </script>
  