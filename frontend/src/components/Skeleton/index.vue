<template>
  <div class="w-full bg-white dark:bg-slate-700 shadow-base p-6 rounded-md">
    <table
      class="animate-pulses w-full border-separate border-spacing-4 table-fixed"
    >
      <thead>
        <tr>
          <th scope="col">
            <div class="h-4 bg-[#C4C4C4] dark:bg-slate-500"></div>
          </th>
          <th scope="col">
            <div class="h-4 bg-[#C4C4C4] dark:bg-slate-500"></div>
          </th>
          <th scope="col">
            <div class="h-4 bg-[#C4C4C4] dark:bg-slate-500"></div>
          </th>
          <th scope="col">
            <div class="h-4 bg-[#C4C4C4] dark:bg-slate-500"></div>
          </th>
          <th scope="col">
            <div class="h-4 bg-[#C4C4C4] dark:bg-slate-500"></div>
          </th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <tr v-for="(item, i) in items" :key="i">
          <td>
            <div class="h-2 bg-[#C4C4C4] dark:bg-slate-500"></div>
          </td>
          <td>
            <div class="h-2 bg-[#C4C4C4] dark:bg-slate-500"></div>
          </td>
          <td>
            <div class="h-2 bg-[#C4C4C4] dark:bg-slate-500"></div>
          </td>
          <td>
            <div class="h-2 bg-[#C4C4C4] dark:bg-slate-500"></div>
          </td>
          <td>
            <div class="h-2 bg-[#C4C4C4] dark:bg-slate-500"></div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
export default {
  props: {
    count: {
      type: Number,
      default: 6,
    },
  },

  computed: {
    items() {
      return Array.from({ length: this.count });
    },
  },
};
</script>
<style>
@keyframes pulses{
  50%{
    opacity: .5;
  }
}
.animate-pulses{
  animation:pulses 2s cubic-bezier(0.4, 0, 0.6, 1) infinite
}
</style>