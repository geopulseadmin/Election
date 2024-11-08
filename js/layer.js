var districtCache = {};
var geoURL = "https://info.dpzoning.com/geoserver/Mojani/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=Mojani:Villages_Boundary&outputFormat=json";

var map = L.map("map", {}).setView([18.8655, 76.7455], 6, L.CRS.EPSG4326);

var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
});

var stamen = L.tileLayer(
    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}'
).addTo(map);
var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom:21,
}).addTo(map);

var Esri_WorldImagery = L.tileLayer(
    "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {
    attribution: "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community"
});

var baseURL = "https://info.dpzoning.com/geoserver/Mojani/wms";

var Villages_Boundary = L.tileLayer.wms(baseURL, {
    layers: "Villages",
    format: "image/png",
    transparent: true,
    tiled: true,
    version: "1.1.0",
    opacity: 1,
}).addTo(map);

var Legislative_Assembly_Boundary = L.tileLayer.wms(baseURL, {
    layers: "Legislative_Assembly_Boundary",
    format: "image/png",
    transparent: true,
    tiled: true,
    version: "1.1.0",
    opacity: 1,
}).addTo(map);

var District_Boundary = L.tileLayer.wms(baseURL, {
    layers: "District_Boundary",
    format: "image/png",
    transparent: true,
    tiled: true,
    version: "1.1.0",
    opacity: 1,
}).addTo(map);

var baseLayers = { 
    "OpenStreetMap": osm,
    "stamen": stamen,
};

var WMSlayers = {
    "Villages Boundary": Villages_Boundary,
    "Legislative Assembly Boundary": Legislative_Assembly_Boundary,
    "District Boundary": District_Boundary,
};

var control = new L.control.layers(baseLayers, WMSlayers);
control.setPosition('topright');

map.zoomControl.remove();

L.control.zoom({
    position: 'bottomright'
}).addTo(map);

map.on("zoomend", function () {
    if (map.getZoom() >21) {
        if (!map.hasLayer(stamen)) {
            map.removeLayer(osm);
            map.addLayer(stamen);
        }
    } else {
        if (!map.hasLayer(osm)) {
            map.removeLayer(stamen);
            map.addLayer(osm);
        }
    }
});



