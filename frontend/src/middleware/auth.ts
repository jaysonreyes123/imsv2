import router from "@/router";
import { useAuthStore } from "@/stores/auth"
export default function auth({ next } : {next:any}){
    const auth_store = useAuthStore();
    auth_store.session();
    const bearerToken = localStorage.getItem("token");
    if(auth_store.authenticated && bearerToken){
        return next();
    }
    else{
        return next({name:'auth',params:{auth_action:'login'}})
    }
}