<template>
    <FullScreenLayout>
      <div class="relative p-6 bg-white z-1 dark:bg-gray-900 sm:p-0">
        <div class="relative flex flex-col justify-center w-full h-screen lg:flex-row dark:bg-gray-900">
          <div class="relative bg-[url(/images/background/background.jpg)] bg-no-repeat bg-center bg-cover items-center hidden w-full h-full lg:w-1/2 bg-brand-950 dark:bg-white/5 lg:grid"
          >
              <!-- <img :src="background" class="w-full h-full object-cover" /> -->
              <!-- <div class="flex items-center justify-center z-1">
                <common-grid-shape />
                <div class="flex flex-col items-center max-w-xs">
                  <router-link to="/" class="block mb-4">
                    <img width="{231}" height="{48}" src="/images/logo/auth-logo.svg" alt="Logo" />
                  </router-link>
                  <p class="text-center text-gray-400 dark:text-white/60">
                    Free and Open-Source Tailwind CSS Admin Dashboard Template
                  </p>
                </div>
              </div> -->
            </div>
            <Signin v-if="$route.params.auth_action == 'login'"/>
            <ForgotPassword v-else-if="$route.params.auth_action == 'forgot-password'"/>
            <ResetPassword v-else-if="$route.params.auth_action == 'reset-password'"/>
            <Otp v-else-if="$route.params.auth_action == 'otp'"/>
        </div>
      </div>
    </FullScreenLayout>
  </template>  
<script>
  import Otp from './Otp.vue';
  import CommonGridShape from '@/components/common/CommonGridShape.vue'
  import FullScreenLayout from '@/components/layout/FullScreenLayout.vue'
  import Signin from './Signin.vue';
  import ForgotPassword from './ForgotPassword.vue';
  import ResetPassword from './ResetPassword.vue';
  import { useAuthStore } from '@/stores/auth';
  const auth_store = useAuthStore();
  export default{
    components:{
      CommonGridShape,
      FullScreenLayout,
      Signin,
      ForgotPassword,
      ResetPassword,Otp
    },
    created(){
      auth_store.loading = false;
      this.$watch(
        ()=>this.$route.params.auth_action,
        (action)=>{
          document.title = "IMS | "+action.toUpperCase()
        }
      );
      document.title = "IMS | "+this.$route.params.auth_action.toUpperCase();
    }
  }
</script>