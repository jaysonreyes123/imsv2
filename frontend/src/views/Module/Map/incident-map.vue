<template>
    <div class="shadow-lg">
        <div class="lg:grid grid-cols-5 rounded-md">
            <div  :class="legend ? 'col-span-4' : 'col-span-5' ">
                <MapboxMap
                :class="classHeight"
                @mb-created="(mapInstance) => map = mapInstance"
                access-token="pk.eyJ1IjoiamF5c29ucmV5ZXMyNiIsImEiOiJjbGd1aDViYXUwZzM2M3BsamlpdWtjbzBsIn0.DmYf96Yhwg7vip5Qpzghnw"
                map-style="mapbox://styles/mapbox/streets-v11"
                :center="[121.02430283862415,14.554636747570202]"
                :zoom="12"
                >
                <MapboxGeocoder 
                countries="PH"
                />
  
                <MapboxMarker v-if="incident_map_ !== undefined" v-for="(item,index) in incident_map_.features " :key="index" :lng-lat="item.geometry.coordinates">
                    <template v-slot:popup>
                        <div style="width: 300px">
                            <p class="text-base"><b>Incident</b></p>
                            <div class="text-xs mt-1">
                                <router-link class="text-blue-600" :to="`/view/incidents/${item.properties.id}`">
                                    {{ item.properties.incident_no }}
                                </router-link>
                                <p><b>Status: </b>{{ item.properties.status }}</p>
                                <p><b>Location: </b>{{ item.properties.location }}</p>
                            </div>
                        </div>
                    </template>
                    <p class="custom-marker">
                       <span class="pulsing-dot-red"></span>
                    </p>
                </MapboxMarker>
                <MapboxMarker v-if="resources_map_ !== undefined" v-for="(item,index) in resources_map_.features " :key="index" :lng-lat="item.geometry.coordinates">
                    <template v-slot:popup>
                        <div style="width: 300px">
                            <p class="text-base"><b>Resources</b></p>
                            <div class="text-xs mt-1">
                                <router-link class="text-blue-600" :to="`/view/resources/${item.properties.id}`">
                                    {{ item.properties.name }}
                                </router-link>
                                <p><b>Type: </b>{{ item.properties.type }}</p>
                                <p><b>Status: </b>{{ item.properties.status }}</p>
                            </div>
                        </div>
                    </template>
                    <p class="custom-marker">
                       <span class="pulsing-dot-black"></span>
                    </p>
                </MapboxMarker>
                </MapboxMap>
            </div>
            <div class="bg-white dark:bg-slate-800 p-5 h-screen overflow-x-auto" v-if="legend">
                <div>
                    <p class="text-sm font-bold">Current incidents</p>
                    <ul class="list-item mt-1">
                        <li @click="flyto(item.geometry.coordinates)" v-for="(item,index) in incident_map_.features" :key="index"
                        class="hover:bg-slate-100 p-2 cursor-pointer flex items-center border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                            <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                <div class="text-xs text-slate-600 dark:text-slate-300 overflow-hidden text-ellipsis whitespace-nowrap flex">
                                <span class="block h-[15px] w-[15px] bg-red-500 rounded-full bg-[#FF0000] mr-2 " ></span> {{ item.properties.incident_no }}
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <br>
                <div v-if="resources_map_ != ''">
                    <p class="text-sm font-bold">Current Resources</p>
                    <ul class="list-item mt-1">
                        <li v-if="resources_map_ !== undefined" @click="flyto(item.geometry.coordinates)" v-for="(item,index) in resources_map_.features" :key="index"
                        class="hover:bg-slate-100 p-2 cursor-pointer flex items-center border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                            <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                <div class="text-xs text-slate-600 dark:text-slate-300 overflow-hidden text-ellipsis whitespace-nowrap flex">
                                <span class="block h-[15px] w-[15px] rounded-full bg-[#000000] mr-2 " ></span> {{ item.properties.name }}
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
  
  </template>
  <script>
  import { MapboxMap, MapboxMarker,MapboxGeocoder,MapboxImages,MapboxLayer   } from '@studiometa/vue-mapbox-gl';
  import Card from "@/components/Card/index.vue";
  import '@mapbox/mapbox-gl-geocoder/lib/mapbox-gl-geocoder.css';
  import 'mapbox-gl/dist/mapbox-gl.css';
  import { ref } from 'vue';
  const map = ref();
  const incident_map_ = ref([]);
  const resources_map_ = ref([]);
  export default { 
      props:{
          legend:{
              type:Boolean,
              default:true
          },
          classHeight:{
              type:String,
              default:'h-screen'
          }
      },
      components:{
          MapboxMap,
          MapboxMarker,
          MapboxGeocoder,
          MapboxImages,
          MapboxLayer,
          Card,
  
      },
      data(){
        return{
            coordinates:[0,0],
            incident_map_,
            resources_map_,
            map
  
        }
      },
      mounted(){
        this.incident_map();
        this.resources_map();
      },
      methods:{
        async incident_map(){
            try {
                const response = await this.$axios.get("map/incidents");
                this.incident_map_ = response.data.data;
            } catch (error) {
                console.log(error)
            }
        },
        async resources_map(){
            try {
                const response = await this.$axios.get("map/resources");
                this.resources_map_ = response.data.data;
            } catch (error) {
                console.log(error)
            }
        },
        flyto(coordinates){
            map.value.flyTo({
                center:coordinates,
                zoom:7
            })
        }
      },
  }
  </script>
  <style scoped>
  .legend-container{
    position: absolute;
    right: 25px;
    top: 70px;
    background: white;
    border-radius: 5px;
    height: 70vh;
    width: 210px;
    z-index: 999;
  }
  .pulsing-dot-red {
    background: red;
    border-radius: 100%;
    display: inline-block;
    height: 20px;
    width: 20px;
    border: 1px solid white;
    box-shadow: 0 0 0 0 rgba(255, 0, 0, 1);
    animation: pulse_red 1.4s infinite;
  }
  
  .pulsing-dot-black {
    background: black;
    border-radius: 100%;
    display: inline-block;
    height: 20px;
    width: 20px;
    border: 1px solid white;
    box-shadow: 0 0 0 0 rgba(0, 0, 0, 1);
    animation: pulse_black 1.5s infinite;
  }
  
  @keyframes pulse_red {
    0% {
        transform: scale(0.80);
        box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.8);
    }
  
    70% {
        transform: scale(1);
        box-shadow: 0 0 0 15px rgba(255, 0, 0, 0);
    }
  
    100% {
        transform: scale(0.80);
        box-shadow: 0 0 0 0 rgba(255, 0, 0, 0);
    }
  }
  
  @keyframes pulse_black {
    0% {
        transform: scale(0.80);
        box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.8);
    }
  
    70% {
        transform: scale(1);
        box-shadow: 0 0 0 15px rgba(0, 0, 0, 0);
    }
  
    100% {
        transform: scale(0.80);
        box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
    }
  }
  </style>