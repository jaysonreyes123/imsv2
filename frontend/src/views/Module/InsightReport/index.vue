<template>
    <div>
       <Card class="shadow-md">
        <div id="insight-report-content">
          <content/>
          <br>
        </div>
      </Card>
    </div>
  </template>
  
  <script>
  import Card from "@/components/Card/index.vue";
  import content from "./content.vue";
  export default {
    components:{
        content,Card
    },
  };
  </script>

  