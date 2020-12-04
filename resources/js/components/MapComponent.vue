<template>
    <div class="row h-100">
        <div class="col">
            <h4>Map</h4>
            <div class="map" ref="map"></div>
        </div>

        <div class="col-auto">
            <h4>Vehicles</h4>

            <ul class="list-group list-unstyled">
                <li class="mt-2" v-for="vehicle in vehicles" :key="vehicle.id">
                    <a href="#" class="btn btn-primary" @click="showVehicle(vehicle.id)">
                        {{ vehicle.name }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import leaflet from 'leaflet';

    export default {
        data() {
            return {
                map: {},
                vehicles: {},
                markers: [],
            };
        },

        async mounted() {
            this.map = L.map(this.$refs.map).setView(
                // Latvijas Universitāte
                [56.95099139764564, 24.11541585860705],
                12
            );

            L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18,
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: process.env.MIX_MAPBOX_API,
            }).addTo(this.map);

            this.icon = new L.Icon.Default();
            this.icon.options.shadowSize = [0, 0];

            await this.update();
            this.markerUpdateLoop();
        },

        methods: {
            async update() {
                const response = await axios.get('/api/vehicles');
                this.vehicles = response.data;
            },

            markerUpdateLoop() {
                setInterval(async () => {
                    await this.update();
                }, 10 * 1000);
            },

            updateMarkers() {
                // Remove out-of-date markers on the map
                for (const marker of this.markers) {
                    this.map.removeLayer(marker);
                }

                this.markers = [];

                // Add new markers back to the map
                for (const vehicle of this.vehicles) {
                    const marker = L.marker(
                        [vehicle.latitude, vehicle.longitude],
                        { icon: this.icon }
                    ).addTo(this.map);

                    marker.bindPopup(vehicle.name);
                    marker.id = vehicle.id;

                    this.markers.push(marker);
                }
            },

            showVehicle(id) {
                const marker = this.markers.find(m => m.id === id);

                this.map.setView(marker._latlng, 13);
                marker.openPopup();
            },
        },

        watch: {
            vehicles(fresh, old) {
                this.updateMarkers();
            },
        },
    }
</script>
