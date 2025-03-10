<template lang="">
    <div>
        <Card title="Map the columns to CRM fields" class="shadow">
            <br>
            <vue-good-table
                :columns="columns"
                :rows="row"
                :fixed-header="true"
                styleClass=" vgt-table table-head "
                :sort-options="{
                    enabled: false,
                }">
                <template v-slot:table-row="props">
                    <span v-if="props.column.field == 'field' ">
                        <v-select 
                            class="!min-w-[150px]" 
                            :options="fields"
                            @option:selected="fieldSelected($event,props.row.originalIndex)"
                            >
                            <template #option="{ value, label,required }">
                                {{label}}<span v-if="required" class="text-red-500">*</span>
                            </template>
                        </v-select>
                    </span>
                </template>
            </vue-good-table>
        </Card>
    </div>
</template>
<script>
import Card from "@/components/Card/index.vue";
import { useImportStore } from "@/stores/import";
import { GetFields } from "../../Other/fields";
import { get_fields } from "../../Other/fields/mapping";
import { useListStore } from "@/stores/list";
const list_store = useListStore();
const import_store = useImportStore();
export default {
    components:{
        Card
    },
    data(){
        return{
            import_store,
            columns: [
                {
                    label: 'Row 1',
                    field: 'row',
                },

                {
                    label: 'Crm fields',
                    field: 'field',
                },
            ],
        }
    },
    computed:{
        row(){
              const get_single_data = [];
              import_store.import_data.map((item)=>{
                  get_single_data.push(
                      {
                          row:item,
                      }
                  )
              })
              return get_single_data;
          },
          fields(){
            const blocks = GetFields(list_store.module);
            const fields = get_fields(blocks);
            return fields;
        }
    },
    methods:{
        fieldSelected(event,index){
            import_store.form.fields[index].field = event.name;
            import_store.form.fields[index].type = event.type;
            import_store.form.fields[index].position = index;
        }
    }
}
</script>
<style lang="">
    
</style>