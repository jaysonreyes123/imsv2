<template>
    <div class="shadow-lg">
        <MapboxMap
                style="height:400px"
                access-token="pk.eyJ1IjoiamF5c29ucmV5ZXMyNiIsImEiOiJjbGd1aDViYXUwZzM2M3BsamlpdWtjbzBsIn0.DmYf96Yhwg7vip5Qpzghnw"
                map-style="mapbox://styles/mapbox/streets-v11"
                :center="center"
                :zoom="12"
                @mb-click="clickMap"
                @mb-created="(mapInstance) => map = mapInstance"
                >
                <MapboxMarker v-if="coordinates_()[0] != 121.02430283862415" :lng-lat="coordinates_()" />
                <MapboxGeocoder 
                countries="PH"
                @mb-result="geolocate"
                :marker="false"
                />
        </MapboxMap>
    </div>

  </template>
  <script>
  import { MapboxMap, MapboxMarker,MapboxGeocoder } from '@studiometa/vue-mapbox-gl';
  import Card from "@/components/Card/index.vue";
  import 'mapbox-gl/dist/mapbox-gl.css';
  import '@mapbox/mapbox-gl-geocoder/lib/mapbox-gl-geocoder.css';
  import {ref} from "vue";
  const center = ref([])
  export default { 
      props:{
        set_coordinates:{
            type:String,
            default:[]
        }
      },
      components:{
          MapboxMap,
          MapboxMarker,
          MapboxGeocoder,
          Card,
      },
      data(){
        return{
            map:null,
            coordinates:[0,0],
            center
        }
      },
      created(){
        if(this.set_coordinates == "" || this.set_coordinates == null){

            center.value = [121.02430283862415,14.554636747570202]
        }
        else{
            center.value = this.coordinates_();
        }
        
      },
      mounted(){
            this.$watch(
                ()=>this.coordinates_(),
                (new_coordinates) => {
                    center.value = new_coordinates;
                }
            );
      },
      methods:{
        geolocate(event){
            const coordinates = event.result.geometry.coordinates;
            this.coordinates=coordinates;
            this.$emit("updateCoordinate",{lng:coordinates[0],lat:coordinates[1]})
        }, 
        clickMap(event){
            const {lng,lat} = event.lngLat;
            this.coordinates=[lng,lat];
            this.$emit("updateCoordinate",event.lngLat)
        },
        coordinates_(){     
            if( this.set_coordinates == ""  || this.set_coordinates == null ){
                return [121.02430283862415,14.554636747570202];
            }   
            const set_coordinates_ = this.set_coordinates.split(',');
            return set_coordinates_;
        }
      }
  }
  </script>
  <style>
.mapboxgl-ctrl-geocoder {
    width:360px !important;
}
</style>