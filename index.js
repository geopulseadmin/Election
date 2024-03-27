var map = L.map("map", {}).setView([18.5690, 73.7432], 9, L.CRS.EPSG4326);

var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
});

var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var Esri_WorldImagery = L.tileLayer(
    "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {
        attribution: "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community"
    }
);

// Load GeoJSON data
var geoJsonData = "Pune_prj.geojson";
// Create a GeoJSON layer
var geoJsonLayer = L.geoJSON.ajax(geoJsonData,{
    style: function (feature) {
        // Your styling logic goes here
        return {
            fillColor: 'gray',  // Change this to the desired fill color
            weight: 2,
            opacity: 1,
            color: 'black',
            dashArray: '3',
            fillOpacity: 0.7
        };
    }
});
// Add GeoJSON layer to the map
geoJsonLayer.addTo(map);

// -----------------layer displayed------------------------
var baseLayers = {
    
};

var WMSlayers = {
    "OpenStreetMap": osm,
    "Esri World Imagery": Esri_WorldImagery,
    "Google Satellite": googleSat,
    "GeoJSON Layer": geoJsonLayer
};

var control = new L.control.layers(baseLayers, WMSlayers).addTo(map);
map.zoomControl.remove();

L.control.zoom({
    position: 'bottomright' // Set position to bottom right
}).addTo(map);


// upload file
function handleFileUpload(input, targetId) {
    const file = input.files[0];
    const filePathContainer = document.getElementById(targetId);

    if (file) {
        filePathContainer.innerHTML = `<p> ${file.name}</p>`;
    } else {
        filePathContainer.innerHTML = ""; // Clear the file name if no file is selected
    }
}


        // Fetch and populate districts, talukas, and villages
        var selectedDistrict;
        var selectedTaluka;

    geoJsonLayer.on('data:loaded', function () {
        populateDropdown('input1', 'District');
        populateDropdown('input2', 'Taluka');
        populateDropdown('input3', 'Village');
    });

    
// Add event listener for the change event on the District dropdown
document.getElementById('input1').addEventListener('change', function () {
    // Get the selected district
    selectedDistrict = this.value;
    selectedTaluka = null;
    // Populate the Taluka dropdown based on the selected district
    populateDropdown('input2', 'Taluka', selectedDistrict);
    // Reset Village dropdown
    populateDropdown('input3', 'Village');
});


// Add event listener for the change event on the Taluka dropdown
document.getElementById('input2').addEventListener('change', function () {
    // Get the selected taluka
    selectedTaluka = this.value;
    // Zoom to the selected Taluka on the map
    zoomToTaluka(selectedTaluka);
    // Populate the Village dropdown based on the selected taluka
    populateDropdown('input3', 'Village', selectedTaluka);
});

// Add event listener for the change event on the Village dropdown
document.getElementById('input3').addEventListener('change', function () {
    // Get the selected village
    selectedVillage = this.value;
    // Zoom to the selected Village on the map
    zoomToVillage(selectedVillage);
});

function populateDropdown(elementId, property, filterValue) {
    var values;
    
    if (filterValue) {
        // If a filter value is provided, get unique values based on the filter
        values = getUniqueValues(geoJsonLayer, property, filterValue);
    } else {
        // Otherwise, get unique values without any filter
        values = getUniqueValues(geoJsonLayer, property);
    }

    populateDropdownOptions(elementId, values);
}

function getUniqueValues(layer, property, filterValue) {
    var uniqueValues = [];

    layer.eachLayer(function (feature) {
        // Check if the feature matches the filterValue for the specified property
        if (!filterValue || feature.feature.properties['District','Taluka'] === filterValue) {
            var value = feature.feature.properties[property];
            if (uniqueValues.indexOf(value) === -1) {
                uniqueValues.push(value);
            }
        }
    });

    return uniqueValues;
}

// Function to zoom to the selected Taluka
function zoomToTaluka(taluka) {
     // Clear previous highlights
     geoJsonLayer.eachLayer(function (layer) {
        layer.setStyle({
            weight: 2,  // Reset the border weight
            color: 'black'  // Reset the border color
        });
    });
    // Clear previous zoom if any
    map.fitBounds(geoJsonLayer.getBounds(), { maxZoom: 11 });
    var selectedLayer;
    // Zoom to the bounds of the features matching the selected Taluka
    geoJsonLayer.eachLayer(function (layer) {
        if (layer.feature.properties['Taluka'] === taluka) {
            selectedLayer = layer;
          
            // Highlight the selected Taluka by changing its style
            layer.setStyle({
                weight: 1,  // Increase the border weight
                color: 'red'  // Change the border color to red (adjust as needed)
            });
        } else {
            map.removeLayer(layer);
        }
    });
    if (selectedLayer) {
        // Get the center of the selected layer's bounds
        var center = selectedLayer.getBounds().getCenter();
        
        // Set the map center and zoom level
        map.setView(center, 11);
    }
}


// Function to zoom to the selected Village
function zoomToVillage(village) {
    // Clear previous highlights
    geoJsonLayer.eachLayer(function (layer) {
        layer.setStyle({
            weight: 2,  // Reset the border weight
            color: 'black'  // Reset the border color
        });
    });

    // Zoom to the bounds of the features matching the selected Village
    geoJsonLayer.eachLayer(function (layer) {
        if (layer.feature.properties['Village'] === village) {
            map.fitBounds(layer.getBounds(), { maxZoom: 15 });

            // Highlight the selected Village by changing its style
            layer.setStyle({
                weight: 1,  // Increase the border weight
                color: 'blue'  // Change the border color to blue (adjust as needed)
            });
        } else {
            map.removeLayer(layer);
        }
    });
}


function populateDropdownOptions(elementId, values) {
    var dropdown = document.getElementById(elementId);
    dropdown.innerHTML = ''; // Clear existing options

    values.forEach(function (value) {
        var option = document.createElement('option');
        option.text = value;
        option.value = value;
        dropdown.add(option);
    });
}
$(document).ready(function() {
    $(".draggable-modal").draggable({
      handle: ".modal-header" // Makes the modal draggable only when clicking on the header
    });
  });
  function handleFileUpload(input, targetId) {
    // Your existing handleFileUpload function logic
  }

   