<template>
    <div class="mb-4 flex justify-end" v-if="addMarker">
        <Button
            text="New Marker"
            btnClass="btn-primary shadow-md mr-2"
            icon="heroicons-outline:plus"
            iconPosition="left"
            iconClass="text-lg"
            @click="openModal"
        />
    </div>
    <div class="shadow-lg">
        <div class="lg:grid grid-cols-5 rounded-md">
            <div  :class="legend ? 'col-span-4' : 'col-span-5' ">
                <MapboxMap
                :class="classHeight"
                @mb-created="(mapInstance) => map = mapInstance"
                access-token="pk.eyJ1IjoiaW1zYmF5YW45MTEiLCJhIjoiY204Zmhyc3Q1MGRhaTJyb3RkenQ3MmVraSJ9.3X2VHHuxYjG9dC03Y8crqg"
                map-style="mapbox://styles/mapbox/streets-v11"
                :center="[121.02430283862415,14.554636747570202]"
                :zoom="12"
                :minZoom="11"
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

                <MapboxMarker v-if="map_markers_ !== undefined" v-for="(item,index) in map_markers_.features " :key="index" :lng-lat="item.geometry.coordinates">
                    <template v-slot:popup>
                        <div>
                            <p class="text-base"><b></b></p>
                            <div class="text-xs mt-1">
                                <h1 class="font-bold text-lg">
                                    {{ item.properties.name }}
                                </h1>
                                <p><b>Location: </b>{{ item.properties.location }}</p>
                                <p><b >Link: </b><a class="text-blue-600" :href="item.properties.link" target="_blank">{{ item.properties.link }}</a></p>
                                <iframe class="w-full h-[200px]"  src="http://localhost:5173/map/incident-map" />
                            </div>
                        </div>
                    </template>
                    <p class="custom-marker">
                       <span class="pulsing-dot-blue"></span>
                    </p>
                </MapboxMarker>
                </MapboxMap>
            </div>
            <div class="bg-white dark:bg-slate-800 p-5 h-screen overflow-x-auto" v-if="legend">
                <div>
                    <p class="text-sm font-bold">Active Incidents</p>
                    <ul class="list-item mt-1">
                        <li @click="flyto(item.geometry.coordinates)" v-for="(item,index) in incident_map_.features" :key="index"
                        class=" hover:bg-slate-200 dark:hover:bg-slate-600 p-2 cursor-pointer flex items-center border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                        <span class="block h-[15px] w-[15px] bg-red-500 rounded-full bg-[#FF0000] mr-2 " ></span>
                            <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                <div class="text-xs text-slate-600 dark:text-slate-300 overflow-hidden text-ellipsis whitespace-nowrap flex">
                                    {{ item.properties.incident_no }}
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
                        class="hover:bg-slate-200 dark:hover:bg-slate-600 p-2 cursor-pointer flex items-center border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                        <span class="block h-[15px] w-[15px] rounded-full bg-[#000000] mr-2 " ></span>
                            <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                <div class="text-xs text-slate-600 dark:text-slate-300 overflow-hidden text-ellipsis whitespace-nowrap flex">
                                     {{ item.properties.name }}
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-if="map_markers_ != ''">
                    <p class="text-sm font-bold">Map Markers</p>
                    <ul class="list-item mt-1">
                        <li v-if="map_markers_ !== undefined" @click="flyto(item.geometry.coordinates)" v-for="(item,index) in map_markers_.features" :key="index"
                        class="hover:bg-slate-200 dark:hover:bg-slate-600 p-2 cursor-pointer flex items-center border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                        <span class="block h-[15px] w-[15px] rounded-full bg-blue-500 mr-2 " ></span>
                            <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                <div class="text-xs text-slate-600 dark:text-slate-300 overflow-hidden text-ellipsis whitespace-nowrap flex">
                                     {{ item.properties.name }}
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <Modal :action_modal="action_modal" @closeModal="closeModal"  @closeModalSave="closeModalSave" />
  </template>
  <script>
  import Modal from "./modal.vue";
  import Button from "@/components/Button/index.vue";
  import Icon from "@/components/Icon/index.vue";
  import { MapboxMap, MapboxMarker,MapboxGeocoder,MapboxImages,MapboxLayer   } from '@studiometa/vue-mapbox-gl';
  import Card from "@/components/Card/index.vue";
  import '@mapbox/mapbox-gl-geocoder/lib/mapbox-gl-geocoder.css';
  import 'mapbox-gl/dist/mapbox-gl.css';
  import { ref } from 'vue';
  const map = ref();
  const incident_map_ = ref([]);
  const resources_map_ = ref([]);
  const map_markers_ = ref([]);
  const action_modal = ref(false);
  export default { 
      props:{
          legend:{
              type:Boolean,
              default:true
          },
          classHeight:{
              type:String,
              default:'md:h-screen h-[500px]'
          },
          addMarker:{
            type:Boolean,
            default:true
          }
      },
      components:{
          MapboxMap,
          MapboxMarker,
          MapboxGeocoder,
          MapboxImages,
          MapboxLayer,
          Card,
          Button,
          Icon,
          Modal
  
      },
      data(){
        return{
            coordinates:[0,0],
            incident_map_,
            resources_map_,
            map_markers_,
            map,
            action_modal
  
        }
      },
      mounted(){
        this.incident_map();
        this.resources_map();
        this.marker_map();
      },
      methods:{
        openModal(){
            action_modal.value = true;
        },
        closeModal(){
            action_modal.value = false;
        },
        closeModalSave(data){
            action_modal.value = false;
            const map_data = {
                type:"Feature",
                properties:{
                    id:data.id,
                    link:data.link,
                    location:data.location,
                    name:data.name
                },
                geometry:{
                    coordinates:data.coordinates.split(","),
                    type:"Point"
                }
            };
            map_markers_.value.features.push(map_data);
        },
        async incident_map(){
            try {
                const response = await this.$axios.get("map/incidents");
                incident_map_.value = response.data.data;
            } catch (error) {
                console.log(error)
            }
        },
        async resources_map(){
            try {
                const response = await this.$axios.get("map/resources");
                resources_map_.value = response.data.data;
            } catch (error) {
                console.log(error)
            }
        },
        async marker_map(){
            try {
                const response = await this.$axios.get("map/markers");
                map_markers_.value = response.data.data;
            } catch (error) {
                console.log(error)
            }
        },
        flyto(coordinates){
            map.value.flyTo({
                center:coordinates,
                zoom:14
            })
        }
      },
  }
  </script>
  <style>
  .mapboxgl-popup-content{
    width: 399px !important;
  }
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
    height: 30px;
    width: 30px;
    border: 1px solid white;
    box-shadow: 0 0 0 0 rgba(255, 0, 0, 1);
    animation: pulse_red 1.4s infinite;
  }
  
  .pulsing-dot-black {
    background: black;
    border-radius: 100%;
    display: inline-block;
    height: 30px;
    width: 30px;
    border: 1px solid white;
    box-shadow: 0 0 0 0 rgba(0, 0, 0, 1);
    animation: pulse_black 1.5s infinite;
  }

  .pulsing-dot-blue {
    background: rgb(59,130,246);
    border-radius: 100%;
    display: inline-block;
    height: 30px;
    width: 30px;
    border: 1px solid white;
    box-shadow: 0 0 0 0 rgba(59,130,246,1);
    animation: pulse_blue 1.6s infinite;
  }
  
  @keyframes pulse_red {
    0% {
        transform: scale(0.80);
        box-shadow: 0 0 0 0 rgba(255, 0, 0, .8);
    }
  
    70% {
        transform: scale(1.1);
        box-shadow: 0 0 0 23px rgba(255, 0, 0, 0);
    }
  
    100% {
        transform: scale(0.80);
        box-shadow: 0 0 0 0 rgba(255, 0, 0, 0);
    }
  }
  
  @keyframes pulse_blue {
    0% {
        transform: scale(0.80);
        box-shadow: 0 0 0 0 rgba(59,130,246,0.8);
    }
  
    70% {
        transform: scale(1.1);
        box-shadow: 0 0 0 23px rgba(59,130,246,0);
    }
  
    100% {
        transform: scale(0.80);
        box-shadow: 0 0 0 0 rgba(59,130,246,0);
    }
  }

  @keyframes pulse_black {
    0% {
        transform: scale(0.80);
        box-shadow: 0 0 0 0 rgba(59 130 246,0.8);
    }
  
    70% {
        transform: scale(1.1);
        box-shadow: 0 0 0 23px rgba(0, 0, 0, 0);
    }
  
    100% {
        transform: scale(0.80);
        box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
    }
  }
  </style>