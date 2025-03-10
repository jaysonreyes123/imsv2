<template lang="">
    <div>
        <Loading v-model:active="import_store.loading"/>
        <Modal 
            sizeClass="max-w-5xl" :title="`Import  ${module_store.moduleDetails.label}`"
            :activeModal="import_store.modal" 
            @close="closeModal">
            <div class="flex z-[5] items-center relative justify-center md:mx-8">
                <div
                    class="relative z-[1] items-center item flex flex-start flex-1 last:flex-none group"
                    v-for="(item, i) in steps"
                    :key="i">

                    <div
                    :class="`   ${
                        import_store.step >= i
                        ? 'bg-slate-900 text-white ring-slate-900 ring-offset-2 dark:ring-offset-slate-500 dark:bg-slate-900 dark:ring-slate-900'
                        : 'bg-white ring-slate-900 ring-opacity-70  text-slate-900 dark:text-slate-300 dark:bg-slate-600 dark:ring-slate-600 text-opacity-70'
                    }`"
                    class="transition duration-150 icon-box md:h-12 md:w-12 h-7 w-7 rounded-full flex flex-col items-center justify-center relative z-[66] ring-1 md:text-lg text-base font-medium"
                    >
                    <span v-if="import_store.step <= i"> {{ i + 1 }}</span>
                    <span v-else class="text-3xl">
                        <Icon icon="bx:check-double" />
                    </span>
                    </div>
                    <div
                    class="absolute top-1/2 h-[2px] w-full"
                    :class="
                        import_store.step >= i
                        ? 'bg-slate-900 dark:bg-slate-900'
                        : 'bg-[#E0EAFF] dark:bg-slate-700'
                    "
                    ></div>
                    <div
                    class="absolute top-full text-base md:leading-6 mt-3 transition duration-150 md:opacity-100 opacity-0 group-hover:opacity-100"
                    :class="
                        import_store.step >= i
                        ? ' text-slate-900 dark:text-slate-300'
                        : 'text-slate-500 dark:text-slate-300 dark:text-opacity-40'
                    "
                    >
                    <span class="w-max">{{ item.title }}</span>
                    </div>
                </div>
            </div>
  
            <div class="conten-box mt-14 border-t border-slate-100 dark:border-slate-700 -mx-6 px-6 pt-6">
                <div v-if="import_store.step == 0">
                    <Step1/>
                </div>
                <div v-if="import_store.step == 1">
                    <Step2/>
                </div>
                <div v-if="import_store.step === 2">
                    <Skeleton v-if="import_store.loading"/>
                    <Step3  v-else/>
                </div>
                <div v-if="import_store.step === 3">
                    <Step4/>
                </div>
            </div>
            <template #footer>
                <div :class="import_store.step > 0 ? 'flex justify-end' : ' text-right'" >
                    <Button
                        :text="import_store.step !== this.steps.length - 1 ? 'next' : 'Close'"
                        @click.prevent="save()"
                        :isLoading="import_store.loading"
                        :isDisabled="import_store.form.file == '' ? true : false "
                        btnClass="btn-primary px-8"
                    />
                </div>
            </template>
        </Modal>
    </div>
</template>
<script>
import Icon from "@/components/Icon/index.vue";
import Button from "@/components/Button/index.vue";
import Modal from '@/components/Modal/index.vue';
import Step1 from "./components/step1.vue";
import Step2 from "./components/step2.vue";
import Step3 from "./components/step3.vue";
import Step4 from "./components/step4.vue";
import { useImportStore } from '@/stores/import';
import { useModuleStore } from "@/stores/module";
import { useListStore } from "@/stores/list";
import { GetFields } from "../Other/fields";
import { get_fields, setForm } from "../Other/fields/mapping";
const import_store = useImportStore();
const module_store = useModuleStore();
const list_store = useListStore();
export default {
    components:{
        Modal,Button,Icon,
        Step1,Step2,Step3,Step4
    },
    data(){
        return{
            module_store,
            import_store,
            steps: [
                {
                    id: 1,
                    title: "Upload csv file",
                },
                {
                    id: 2,
                    title: "Duplicate handling",
                },
                {
                    id: 3,
                    title: "Field mapping",
                },
                {
                    id: 4,
                    title: "Result",
                },
            ]
        }
    },
    beforeMount(){
        this.$watch(
            ()=>import_store.modal,
            (bool) => {
                if(bool){
                    list_store.module = this.$route.params.module;
                    import_store.loading = false;
                    import_store.form.module = this.$route.params.module;
                    import_store.form.file = "";
                    import_store.form.hasheader = false;
                    import_store.form.duplicate_field = [];
                    import_store.form.fields = [];
                    import_store.form.duplicate_option = 1;
                    import_store.module_fields = [];
                    import_store.step = 0;
                }
            }
        )
    },
    methods:{
        closeModal(){
            if(import_store.step == this.steps.length -1){
                import_store.clear();
                list_store.load();
            }
            import_store.modal = false;
        },
        save(){
            if(import_store.step == this.steps.length -1){
                this.closeModal();
                return false;
            }
            if(import_store.step == 1){
                import_store.getImport();
               
            }
            else if(import_store.step == 2){
                const blocks = GetFields(list_store.module);
                const import_fields = get_fields(blocks);
                const {required} = setForm(blocks);
                const selected_fields = import_store.form.fields.map(item=>{
                    return item.field
                })
                const requireds = required.filter(x => !selected_fields.includes(x.field))
                const duplicates = selected_fields.filter((item, index) => selected_fields.indexOf(item) !== index);
                let error = "";
                if(requireds.length > 0){
                    requireds.map(item=>{
                        error+=`<p class="text-red-500">Please map mandatory fields <b> ${item.label}</b> </p>`;
                    })
                }
                console.log(import_store.module_fields);
                if(duplicates.length > 0){
                    duplicates.map(item=>{
                        if(item != ''){
                            const label = import_fields.find(x => x.name == item)
                            error+=`<p class="text-red-500">Field mapped more than once<b> ${label.label}</b> </p>`;
                        }
                    })
                }
                if(error != ""){
                    this.$swal.fire({
                        icon: "error",
                        title: "Something wrong",
                        html: error,
                    });
                    return false;
                }
                import_store.form.fields = import_store.form.fields.filter(item=>item.field);
                import_store.saveImport();
            }
            else{
                import_store.step++;
            }
        }
    }
}
</script>
<style lang="">
    
</style>