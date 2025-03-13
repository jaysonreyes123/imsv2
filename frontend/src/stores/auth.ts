

import { defineStore } from 'pinia';
import { useModuleStore } from './module';
import router from '@/router';
import axiosIns from '@/library/axios';
export const useAuthStore = defineStore('auth',{
    state:()=>{
        return{
            loading:false,
            authenticated:false,
            errors:{
                status:'error',
                message:''
            },
            otp:{
                email:"",
                token:""
            },
            form:{
                email:"",
                password:"",
            },
            resetform:{
                password:"",
                repassword:"",
                token:""
            },
            user_details:{}
        }
    },
    actions: {
        verified(token:string){
            localStorage.setItem("token",token);
            this.userDetails();
            const module_store = useModuleStore();
            module_store.get_modules();
        },
        async verify(){
            try {
                this.loading = true;
                const response = await axiosIns.post("auth/verify",this.otp);
                this.verified(response.data.data);
                this.loading = false;
            } catch (error:any) {
                this.errors.status = 'error';
                this.errors.message = error.response.data.message;
                this.loading = false;
            }
        },
        async login(){
            try {
                this.loading = true;
                const response = await axiosIns.post("auth/login",this.form);
                if(response.data.data.type == "otp"){
                    this.otp.email = response.data.data.email;
                    router.push("/auth/otp");
                }
                else{
                    this.verified(response.data.data);
                }
                this.loading = false;
            } catch (error:any) {
                if(error.response.data.status == 422){
                    this.errors.status = 'error';
                    this.errors.message = error.response.data.message;
                }
                this.loading = false;
            }
        },
        async logout(){
            try {
                const response = await axiosIns.get('auth/logout');
                this.authenticated = false;
                this.user_details = {};
                router.push({name:'auth',params:{auth_action:'login'}});
            } catch (error) {
                router.push({name:'auth',params:{auth_action:'login'}});
            }
        },
        async resetPassword(){
            try {
                this.loading = true;
                this.errors.message = '';
                if(this.resetform.password != this.resetform.repassword){
                    this.errors.status = 'error';
                    this.errors.message = 'Password not matched'
                    this.loading = false;
                    return false;
                }
                let response = await axiosIns.post('auth/reset-password', this.resetform);
                this.errors.status = 'success';
                this.errors.message = 'Reset password successfully. Redirect to login page';
                setTimeout(()=>{
                    router.push({name:'auth',params:{auth_action:'login'}})
                },2000)
                this.loading = false;
            } catch (error:any) {
                if(error.response.status == 422){
                    console.log(error.response)
                    this.errors.status = 'error';
                    this.errors.message = error.response.data.message;
                }
                
                this.loading = false;
            }
        },
        async forgotpassword(){
            try {
                this.loading = true;
                const response = await axiosIns.get('auth/forgot-password/'+this.form.email)
                if(response.data.data == 'success'){
                    this.errors.status = 'success';
                    this.errors.message = 'Email Sent';
                }
                else{
                    this.errors.status = 'error';
                    this.errors.message = response.data.data;
                }
                this.loading = false;
            } catch (error) {
                this.loading = false;
            }
        },
        async userDetails(){
            try {
                this.loading = true;
                const response = await axiosIns.get('users/user_details');
                this.authenticated = true;
                this.user_details = response.data.data;
                this.loading = false;
                router.push("/dashboard");
                
            } catch (error) {
                
            }
        },
        async session(){
            try {
                const response = await axiosIns.get("session");
            } catch (error) {
                router.push("/auth/login");
            }
        }
    },
    persist:true
})