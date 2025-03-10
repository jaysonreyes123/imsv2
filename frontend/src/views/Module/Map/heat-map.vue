<template>
    <div class="shadow-lg relative">
      <div ref="mapContainer" class="map-container h-screen"></div>
      <div class="absolute top-[20px] left-[20px]">
          <div class="flex">
              <VueTailwindDatePicker 
                  v-model="dateValue" 
                  input-classes="block text-xs form-control !bg-white dark:!bg-slate-700 !w-[400px]" 
                  :formatter="formatter"
              />
              <Button
                  text="Search"
                  btnClass="btn-sm btn-primary !px-6"
                  class="ml-1"
                  @click="LoadMap"
              />
          </div>
      </div>
    </div>
  </template>
  <script>
  import Button from "@/components/Button/index.vue";
  import Icon from "@/components/Icon/index.vue";
  import mapboxgl from 'mapbox-gl';
  import { ref } from 'vue';
  import VueTailwindDatePicker from "vue-tailwind-datepicker";
  mapboxgl.accessToken ="pk.eyJ1IjoiamF5c29ucmV5ZXMyNiIsImEiOiJjbGd1aDViYXUwZzM2M3BsamlpdWtjbzBsIn0.DmYf96Yhwg7vip5Qpzghnw";
  const heat_map_data = ref([]);
  const dateValue = ref({
    startDate: "",
    endDate: "",
  });
  const formatter = ref({
    date: 'MMMM DD YYYY',
    month:'MMM'
  })
  const map = ref();
  export default {
  components:{
      Icon,
      Button,
      VueTailwindDatePicker
  },
  data(){
      return{
          heat_map_data,
          dateValue,
          formatter
      }
  },
  mounted(){
      dateValue.value.startDate = "";
      dateValue.value.endDate = "";
      this.heat_map();
  },
  methods:{
      LoadMap(){
          this.loadmapdata();
      },
      dateFormat(date){
          const newdate   = new Date(date);
          const day       = newdate.getDate().toString().padStart(2,"0");
          const month     = (newdate.getMonth() + 1).toString().padStart(2,"0");
          const year      = newdate.getFullYear().toString();
          return [year,month,day].join("-");
      },
      async loadmapdata(){
          const startDate = this.dateFormat(dateValue.value.startDate);
          const endDate = this.dateFormat(dateValue.value.endDate);
          const response = await this.$axios.get("map/heat_map/"+startDate+"/"+endDate);
          map.value.getSource('datasource').setData(response.data.data);
      },
      async heat_map(){
          try {
              const response = await this.$axios.get("map/heat_map");
              map.value = new mapboxgl.Map({
                  container: this.$refs.mapContainer,
                  style: 'mapbox://styles/mapbox/dark-v10',
                  center: [121.02430283862415,14.554636747570202],
                  zoom:12
              });
              map.value.addControl(new mapboxgl.FullscreenControl());
              map.value.addControl(new mapboxgl.NavigationControl());
              map.value.on('load',()=>{
              map.value.addSource('datasource', { type: 'geojson', data: response.data.data });
              map.value.addLayer(
              {
                  'id': 'heatheat',
                  'type': 'heatmap',
                  'source': 'datasource',
                  'maxzoom': 10,
                  'paint': {
                      // increase weight as diameter breast height increases
                      'heatmap-weight': {
                          'property': 'dbh',
                          'type': 'exponential',
                          'stops': [
                              [1, 0],
                              [62, 1]
                          ]
                      },
                      // increase intensity as zoom level increases
                      'heatmap-intensity': {
                          'stops': [
                              [11, 1],
                              [18, 3]
                          ]
                      },
                      // use sequential color palette to use exponentially as the weight increases
                      'heatmap-color': [
                          'interpolate',
                          ['linear'],
                          ['heatmap-density'],
                          0, 'rgba(255, 255, 255,0)',
                          0.2, '#FF0000',
                          0.4, '#FF0000',
                          0.6, '#FF0000',
                          0.8, '#FF0000'
                      ],
                      // increase radius as zoom increases
                      'heatmap-radius': {
                          'stops': [
                              [3, 18],
                              [10, 20]
                          ]
                      },
                      // decrease opacity to transition into the circle layer
                      'heatmap-opacity': {
                          'default': 1,
                          'stops': [
                              [6, 1],
                              [10, 0]
                          ]
                      }
                  }
              },
                  'waterway-label'
              );  
              map.value.addLayer(
              {
                  'id': 'point',
                  'type': 'circle',
                  'source': 'datasource',
                  'minzoom': 9,
                  'paint': {
                      // increase the radius of the circle as the zoom level and dbh value increases
                      'circle-radius': {
                          'property': 'dbh',
                          'type': 'exponential',
                          'stops': [
                              [{ zoom: 10, value: 1 }, 5],
                              [{ zoom: 10, value: 62 }, 10],
                              [{ zoom: 12, value: 1 }, 20],
                              [{ zoom: 12, value: 62 }, 50]
                          ]
                      },
                      'circle-color': {
                          'property': 'dbh',
                          'type': 'exponential',
                          'stops': [
                              [0, 'rgba(255, 255, 255,0)'],
                              [10, '#FF0000'],
                              [20, '#FF0000'],
                              [30, '#FF0000'],
                              [40, '#FF0000'],
                              [50, '#FF0000'],
                              [60, '#FF0000']
                          ]
                      },
                      'circle-stroke-color': 'white',
                      'circle-stroke-width': 1,
                      'circle-opacity': {
                          'stops': [
                              [6, 0],
                              [10, 1]
                          ]
                      }
                  }
              },
                  'waterway-label'
              );
          })
          } catch (error) {
              console.log(error)
          }
      },
  }
  };
  </script>
  
  <style>
  </style>