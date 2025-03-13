<template lang="">
    <div class="p-5">
        <p>{{ currentDate }} {{ currentTime }}</p>
        <br>
        <div class="grid grid-cols-3 gap-4">
            <div v-for="(item,i) in data" :key="i">
                <div class="rounded-md p-4 shadow-md text-center bg-white dark:bg-slate-800">
                    <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                        {{item.title}}
                    </span>
                    <span class="block mb- text-2xl text-slate-900 dark:text-white font-medium">
                        {{item.count}}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
const data = [
    {
        title:"incident Type",
        count:10000
    },
    {
        title:"incident Status",
        count:10000
    },
    {
        title:"incident Priority",
        count:10000
    }
];
export default {
    data(){
        return{
            data,
            currentDate:"",
            currentTime:""
        }
    },
    mounted(){
        this.updateTime();
        setInterval(this.updateTime, 1000);
    },
    methods:{
        updateTime() 
        {
            const now = new Date();

            // Format date and time
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const seconds = now.getSeconds();
            const formattedTime = `${hours}:${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;

            // Update date and time
            this.currentTime = formattedTime;
            this.currentDate = now.toLocaleDateString();
        }      
    }
}
</script>
<style lang="">
    
</style>