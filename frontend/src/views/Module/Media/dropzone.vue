<template lang="">
    <div>
        <div class="lg:grid lg:grid-cols-2 gap-4 mb-4">
            <div class="fromGroup relative">
                <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">
                    File Title <span class="text-red-500">*</span>
                </label>
                <Textinput v-model="media_store.form.filetitle" />
                <label class="validation-label" v-if="media_store.errors.filetitle !=''" >{{media_store.errors.filetitle}}</label>
            </div>
            <div class="fromGroup relative">
                <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">
                    Assigned To <span class="text-red-500">*</span>
                </label>
                <v-select 
                    v-model="media_store.form.assigned_to"
                    :loading="dropdown_store.dropdownloading['users']"
                    :reduce="option => option.id"
                    placeholder="Select an option"
                    :options="dropdown_store.dropdownlist['users']" />
                <label class="validation-label" v-if="media_store.errors.assigned_to !=''" >{{media_store.errors.assigned_to}}</label>
            </div>
        </div>
        <div class="mb-4">
                <div class="fromGroup relative">
                    <label class="flex-0 mr-6 break-words ltr:inline-block rtl:block input-label">
                        Note
                    </label>
                    <Textarea v-model="media_store.form.note" />
                </div>
            </div>
        <div class="file-uploader">
            <form
            ref="dropzoneForm"
            id="dropzone-media"
            action="action"
            class="border-gray-300 border-dashed dropzone rounded-xl bg-gray-50 p-7 hover:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-brand-500 lg:p-10"
            >
            <div class="dz-message !m-0">
                <div class="mb-[22px] flex justify-center">
                <div
                    class="flex h-[68px] w-[68px] items-center justify-center rounded-full bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-400"
                >
                    <svg
                    class="fill-current"
                    width="29"
                    height="28"
                    viewBox="0 0 29 28"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 10.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z"
                        fill=""
                    />
                    </svg>
                </div>
                </div>

                <h4 class="mb-3 font-semibold text-gray-800 text-theme-xl dark:text-white/90">
                Drag & Drop File Here
                </h4>
                <span
                class="mx-auto mb-5 block w-full max-w-[290px] text-sm text-gray-700 dark:text-gray-400"
                >
                <!-- Drag and drop your PNG, JPG, WebP, SVG images here or browse -->
                 Drag and drop your files where or browse
                </span>

                <span class="font-medium underline cursor-pointer text-theme-sm text-brand-500">
                Browse File
                </span>
            </div>
            </form>
    </div>
      <ul class="list-item space-y-3 h-full overflow-x-auto mt-5">
        <li
            class="flex justify-between items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0"
            v-for="(item, i) in media_store.form.files"
            :key="i">
                <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                    <div class="text-sm text-slate-600 dark:text-slate-300 overflow-hidden text-ellipsis whitespace-nowrap">
                        {{i+1}}. {{item.name}}
                    </div>
                </div>
                <div class="ltr:text-right rtl:text-left">
                        <div class="text-sm font-light text-slate-400 dark:text-slate-400">
                        <Button icon="heroicons-outline:trash"
                        btnClass="inline-flex items-center justify-center h-10 w-10 bg-danger-500 text-lg border rounded border-danger-500 text-white"
                        @click="removeFile(i)" />
                    </div>
                </div>
        </li>
    </ul>
</div>
</template>
<script>
import Textinput from "@/components/Textinput/index.vue";
import Textarea from "@/components/Textarea/index.vue";
import Button from "@/components/Button/index.vue";
import Dropzone from 'dropzone'
import 'dropzone/dist/dropzone.css';
import { useMediaStore } from '@/stores/media';
import { useDropdownStore } from "@/stores/dropdown";
const dropdown_store = useDropdownStore();
const media_store = useMediaStore();
let myDropzone;
export default {
    components:{
        Button,
        Textinput,Textarea,
    },
    data(){
        return{
            media_store,dropdown_store
        }
    },
    beforeMount(){
        this.clear();
        media_store.form.id = this.$route.params.id;
        media_store.form.module = this.$route.params.module;
        if(myDropzone){
            myDropzone.destroy()
        }
    },
    mounted(){
        dropdown_store.get_dropdown('users');
        myDropzone = new Dropzone("#dropzone-media",{
            autoProcessQueue:false,
            addedfile: file => {
                media_store.form.files.push(file)
            }
        });
    },
    methods:{
        clear(){
            media_store.form.files = [];
            media_store.form.assigned_to = "";
            media_store.form.filetitle = "";
            media_store.form.note = "";

        },
         removeFile(index){
            media_store.form.files.splice(index,1);
        }
    }
}
</script>
<style>
.dropzone {
  border: 1px dashed #d0d5dd;
  transition: all 0.3s ease;
}

.dropzone:hover {
  border-color: #465fff;
}

.dropzone .dz-preview {
  margin: 10px;
}

.dropzone .dz-preview .dz-image {
  border-radius: 8px;
}

.dropzone .dz-preview .dz-details {
  padding: 1em;
}

.dropzone .dz-preview .dz-progress {
  height: 10px;
}

.dropzone .dz-preview .dz-progress .dz-upload {
  background: #4f46e5;
}

.dark .dropzone {
  background-color: #111827;
  border-color: #374151;
}

.dark .dropzone:hover {
  border-color: #6366f1;
}
</style>