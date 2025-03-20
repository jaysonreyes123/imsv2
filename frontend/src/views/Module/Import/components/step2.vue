<template lang="">
    <div>
        <Card title="Duplicate record handling" class="shadow">
            <div class="fromGroup relative mt-5">
                <label for="" class="inline-block input-label">Select how duplicate records should be handled</label>
                <v-select 
                    v-model="import_store.form.duplicate_option"
                    placeholder="Select an option" 
                    :reduce="option=>option.value" 
                    :options="options" />
            </div>
            <div class="fromGroup relative" v-if="import_store.form.duplicate_option != 1">
              <p class="mt-5">Select the matching fields to find duplicate records</p>
              <label for="" class="inline-block input-label">Available fields</label>
              <v-select 
                multiple 
                v-model="import_store.form.duplicate_field" 
                :reduce="option=>option.name" 
                :options="fields" 
                :loading="import_store.loading" />
          </div>
        </Card>
    </div>
</template>
<script>
import Card from "@/components/Card/index.vue";
import { useImportStore } from "@/stores/import";
import { GetFields } from "../../Other/fields";
import { get_fields } from "../../Other/fields/mapping";
import { useListStore } from "@/stores/list";
const import_store = useImportStore();
const list_store = useListStore();
const options = 
[
    {
        value:1,
        label:"Skip this step"
    },
    {
        value:2,
        label:"Skip"
    },
    {
        value:3,
        label:"Update"
    }
]
export default {
    components:{
        Card
    },
    data(){
        return{
            options,import_store
        }
    },
    computed:{
        fields(){
            const blocks = GetFields(list_store.module);
            const fields = get_fields(blocks);
            return fields.filter(field => field.type != 'hidden');
        }
    },
}
</script>
<style lang="">
    
</style>