<template lang="">
    <div>
        <div class="w-full  flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="flex gap-1.5 items-center text-md text-gray-500 dark:text-gray-400">
                <router-link v-if="!this.$route.params.option" :to="{name:'list',params:{module:this.$route.params.module}}">
                    {{ title }} 
                </router-link>
                <router-link v-else :to="{name:'list',params:{module:'reports'}}">
                    {{ title }} 
                </router-link>
                <Icon icon="heroicons:chevron-right" />
                    {{ subtitle }} 
            </div>
            <slot name="button"/>
        </div>
    </div>
</template>
<script>
import Icon from "@/components/Icon/index.vue";
import { useModuleStore } from '@/stores/module';
const module_store = useModuleStore();
export default {
    props:{
        title:{
            type:String,
            default:""
        },
        subtitle:{
            type:String,
            default:""
        }
    },
    components:{
        Icon
    },
    data(){
        return{
            module_store
        }
    }
}
</script>
<style lang="">
    
</style>