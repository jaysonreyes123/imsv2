<template>
    <div>
      <Card title="Import result" class="shadow">
        <div class="lg:grid grid-cols-3 mt-3">
          <div>Records Created</div>
          <div class="col-span-2">{{ get_result(1).length }}</div>
        </div>
  
        <div class="lg:grid grid-cols-3 mt-3">
          <div>Records Skip</div>
          <div class="col-span-2">{{ get_result(2).length }}</div>
        </div>
  
        <div class="lg:grid grid-cols-3 mt-3">
          <div>Records Update</div>
          <div class="col-span-2">{{ get_result(3).length }}</div>
        </div>
        <div class="lg:grid grid-cols-3 mt-3" v-if="get_result(0).length > 0">
          <div>Records Error</div>
          <div class="col-span-2 text-red-500">{{ get_result(0).length }}</div>
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
        get_result(status){
            return import_store.import_data.filter(item => item.status == status);
        }
    }
}
</script>
<style lang="">
    
</style>