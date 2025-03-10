<template>
    <div class="flex flex-col flex-1 w-full lg:w-1/2">
        <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
          <div>
            <div class="mb-5 sm:mb-8">
              <h1
                class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md"
              >
              Forgot Your Password?
              </h1>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Enter your email below, and we’ll send you a link to reset your password.
              </p>
            </div>
            <div>
              <Alert
                  v-if="auth_store.errors.status == 'error'  "
                  class="mb-4"
                  variant="error"
                  title=""
                  :message="auth_store.errors.message"
                  :showLink="false"
                />
                <Alert
                  v-else-if="auth_store.errors.status == 'success'  "
                  class="mb-4"
                  variant="success"
                  title=""
                  :message="auth_store.errors.message"
                  :showLink="false"
                />
              <form @submit.prevent="auth_store.forgotpassword()">
                <div class="space-y-5">
                  <!-- Email -->
                  <div>
                    <label
                      for="email"
                      class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                    >
                      Email<span class="text-error-500">*</span>
                    </label>
                    <input
                      v-model="auth_store.form.email"
                      type="email"
                      id="email"
                      name="email"
                      placeholder="Enter you email"
                      class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                    />
                  </div>
                  <!-- Button -->
                  <div>
                    <Button text="Send Reset Link" type="submit" btnClass="btn btn-primary block w-full text-center" :isLoading="auth_store.loading" />
                  </div>
                  <p> Remembered your password? <router-link to="/auth/login" class="font-bold">Log in</router-link> instead.</p>
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
  auth_store.errors.message = '';
  auth_store.errors.status = '';
  auth_store.form.email = '';
  auth_store.loading = false;
  </script>
  