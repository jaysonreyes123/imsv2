import { useAuthStore } from "@/stores/auth"
export default function auth({ next } : {next:any}){
    const auth_store = useAuthStore();
    if(auth_store.authenticated){
        return next();
    }
    else{
        return next({name:'auth',params:{auth_action:'login'}})
    }
}