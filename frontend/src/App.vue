<template>
  <ThemeProvider>
    <SidebarProvider>
      <RouterView />
    </SidebarProvider>
  </ThemeProvider>
</template>

<script>
import ThemeProvider from './components/layout/ThemeProvider.vue'
import SidebarProvider from './components/layout/SidebarProvider.vue'
import { useAuthStore } from './stores/auth';
import { useSystemStore } from './stores/system';
import { ref } from 'vue';
const timer = 300; // 5mins
var remaining_time_interval;
export default{
  components:{
    ThemeProvider,SidebarProvider
  },
  mounted(){
  const auth_store = useAuthStore();
  const system_store = useSystemStore();
 
    this.user_logout.listen('.user-logout',(e)=>{
      setTimeout(()=>{
        if(e.user_id == auth_store.user_details.id && e.remember_token != auth_store.user_details.remember_token){
          this.logout();
        }
      },3000)
    });

    this.session_expired.listen('.session-expired-event',(e)=>{
      setTimeout(()=>{
        if(e.user_id == auth_store.user_details.id){
          this.session();
        }
      },3000)
    });
  },
  methods:{
    logout(){
      this.$swal.fire({
            title: 'You have been logged out',
            text: 'Your account has been logged in another device. Please click okay to login again.',
            icon: 'error',
            confirmButtonText: 'Okay',
            allowOutsideClick: false
        })
        .then(()=>{
            location.href="/auth/login";
        })
    },
    session(){
      this.$swal.fire({
            title: `You have been logged out in ${Math.floor(timer / 60)}  mins`,
            html: 'Remaining time <br> <b>05:00</b>',
            timerProgressBar: true,
            width:600,
            timer:1000*timer,
            icon: 'error',
            confirmButtonText: 'Okay',
            allowOutsideClick: false,
            didOpen: () => {
              const timer_content = this.$swal.getPopup().querySelector("b");
              var time_left = timer;
              remaining_time_interval = setInterval(() => {
                time_left-=1;
                var minutes = Math.floor((time_left % 3600) / 60);
                var seconds = time_left % 60;
                timer_content.textContent = `${minutes >= 10 ? minutes : "0"+minutes}:${seconds >= 10 ? seconds : "0"+seconds}`;
              }, 1000);
            },
            willClose: () => {
              clearInterval(remaining_time_interval);
              setTimeout(()=>{
                location.href="/auth/login?action=session-expired";
              },1000)
            }
        })
        .then(()=>{
            // localStorage.removeItem('remember_token')
            const auth_store = useAuthStore();
            auth_store.session();
        })
    },
  }
}
</script>

<style>
.form-control,.input-control{
    @apply dark:bg-black-500 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800
}
.vs__dropdown-toggle{
    min-height: 40px !important;
    @apply rounded-lg dark:bg-black-500 text-gray-800 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 bg-transparent
}
.vs__selected{
  @apply dark:placeholder:text-white/30 dark:text-white/90
}
.vgt-inner-wrap {
    -webkit-box-shadow: none !important;
    -moz-box-shadow: none !important;
    box-shadow: none !important;
}
.swal2-timer-progress-bar{
  @apply bg-blue-500
}
</style>