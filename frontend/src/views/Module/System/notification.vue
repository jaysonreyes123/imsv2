<template>
    <div>
      <h6>Notifications</h6>
      <br>
      <Card bodyClass="px-6">
        <Menu as="div" class="-mx-6">
          <div
            class="flex justify-between px-4 py-4 border-b border-slate-100 dark:border-slate-600"
          >
          </div>
          <MenuItem
            v-slot="{ active }"
            v-for="(item, i) in notification_store.notifications.logs"
            :key="i"
          >
            <div
              :class="`${
                active
                  ? 'bg-slate-100 dark:bg-slate-600 dark:bg-opacity-70 text-slate-800'
                  : 'text-slate-600 dark:text-slate-300'
              } block w-full px-4 py-2 text-sm mb-2 last:mb-0 cursor-pointer`"
            >
              <div class="flex text-left">
                <div class="flex-none ltr:mr-3 rtl:ml-3">
                <div class="h-8 w-8 bg-white rounded-full flex justify-center items-center ">
                  <Icon :icon="item.icon" class="text-2xl"/>
                </div>
              </div>
                <div class="flex-1">
                  <div
                    :class="`${
                      active
                        ? 'text-slate-600 dark:text-slate-300'
                        : ' text-slate-600 dark:text-slate-300'
                    } text-lg font-semibold `"
                  >
                    {{ item.module }}
                  </div>
                  <div
                    :class="`${
                      active
                        ? 'text-[#68768A] dark:text-slate-200'
                        : ' text-slate-600 dark:text-slate-300'
                    } text-xs leading-4`"
                  >
                    {{ item.name }}
                  </div>
                  <div class="text-secondary-500 dark:text-slate-400 text-xs">
                    {{ item.timestamp }}
                  </div>
                </div>
                <div class="flex-0" v-if="item.status == 1">
                  <span
                    class="h-[10px] w-[10px] bg-danger-500 border border-white rounded-full inline-block"
                  >
                  </span>
                </div>
              </div>
            </div>
          </MenuItem>
        </Menu>
        <div class="flex justify-center px-5 py-3">
            <Button text="Load more" btnClass="btn-outline-dark btn-sm" class="w-full" />
        </div>
      </Card>
    </div>
  </template>
  
<script>
import Button from "@/components/Button/index.vue";
import { MenuItem, Menu } from '@headlessui/vue';
import Icon from "@/components/Icon/index.vue";
import Card from '@/components/Card/index.vue';
import { useSystemStore } from "@/stores/system";
const notification_store = useSystemStore();
export default {
  components: {
      MenuItem,
      Menu,
      Card,
      Icon,
      Button
  },
  data() {
      return {
          notification_store
      };
  },
  beforeMount(){
    notification_store.notifications.current_page = 1;
  },
  mounted(){
    notification_store.notification();
  }
};
</script>
<style lang=""></style>
  