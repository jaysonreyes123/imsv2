<template lang="">
    <div>
        <Card title="Import from csv file" class="shadow">
            <br>
            <div class="lg:grid lg:grid-cols-1">
              <div class="fromGroup relative">
                  <label for="" class="inline-block input-label">Select csv file</label>
                  <input
                    accept=".csv"
                    @change="file"
                    type="file"
                    class="focus:border-ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 shadow-theme-xs transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pl-3.5 file:pr-3 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-none focus:file:ring-brand-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400"
                   />
              </div>
              <br>
              <div>
                <label
                for="checkboxLabelOne"
                class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400"
                >
                <div class="relative">
                    <input type="checkbox" id="checkboxLabelOne" v-model="import_store.form.hasheader" class="sr-only" />
                    <div
                    :class="
                        import_store.form.hasheader
                        ? 'border-brand-500 bg-brand-500'
                        : 'bg-transparent border-gray-300 dark:border-gray-700'
                    "
                    class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] hover:border-brand-500 dark:hover:border-brand-500"
                    >
                    <span :class="import_store.form.hasheader ? '' : 'opacity-0'">
                        <svg
                        width="14"
                        height="14"
                        viewBox="0 0 14 14"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        >
                        <path
                            d="M11.6666 3.5L5.24992 9.91667L2.33325 7"
                            stroke="white"
                            stroke-width="1.94437"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        </svg>
                    </span>
                    </div>
                </div>
                    Has Header
                </label>
            </div>
          </div>
        </Card>
    </div>
</template>
<script>
import Card from "@/components/Card/index.vue";
import { useImportStore } from "@/stores/import";
const import_store = useImportStore();
export default {
    components:{
        Card
    },
    data(){
        return{
            import_store
        }
    },
    methods:{
        file(event){
            import_store.form.file = event.target.files[0];
        }
    }
}
</script>
<style lang="">
    
</style>