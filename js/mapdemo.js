const main_url = "https://info.dpzoning.com/geoserver/";


// Live location on page load
map.on('load', function() {
  LiveLocation();  // Call live location when page loads
});


function LiveLocation() {
  if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
          var lat = position.coords.latitude;
          var lon = position.coords.longitude;

          console.log("Accurate Latitude: " + lat + ", Longitude: " + lon);

          map.setView([lat, lon], 15); // Set view to live location

          // Add marker for live location with lat and long in popup
          L.marker([lat, lon]).addTo(map)
              .bindPopup("Current Location:<br>Lat: " + lat + "<br>Long: " + lon)  // Corrected here
              .openPopup();

      }, function (error) {
          alert("Unable to retrieve your location. Error: " + error.message);
          console.error("Geolocation error: ", error);
      }, {
          enableHighAccuracy: true,  // Try for GPS accuracy
          timeout: 15000,  // Longer timeout for accuracy
          maximumAge: 0   // Ensure no cached location
      });
  } else {
      alert("Geolocation is not supported by this browser.");
  }
}

// Trigger live location when the page loads
$(document).ready(function() {
  LiveLocation();
});

// search type

document.addEventListener('DOMContentLoaded', (event) => {
 var columns =  {"ac_name": "Constituency name", "village": "Village Name", "my_name": "Candidate Name"  };
      var select = document.getElementById("search_type");
      // Populate dropdown with column names
    for (var key in columns) {
      if (columns.hasOwnProperty(key)) {
        var option = document.createElement("option");
        option.text = columns[key];
        option.value = key;
    select.appendChild(option);
        }
    }
  
    // Initialize selected value variable
    let selectedValue;
  
    // Event listener for dropdown change
    $("#search_type").change(function () {
      var selectedValue = $(this).val();
      var selectedText = columns[selectedValue]; // Get corresponding label from columns object
      var input = document.getElementById("searchInputDashboard");
      // Update input placeholder and clear input value
      var selectedValue = select.value;
      input.placeholder = "Search " + selectedText;
      input.value = "";
  
  
      // Call autocomplete with empty array and selected column
      autocomplete(input, [], selectedValue);
  
      // Trigger search based on the selected column immediately after selecting
      if (selectedValue) {
        getValues(function (data) {
          autocomplete(input, data, selectedValue); // Call autocomplete with fetched data and selected column
        });
      }
  
      function getValues(callback) {
        let geoServerURL;

        // Determine the correct layer based on selectedValue
        if (selectedValue === 'ac_name' || selectedValue === 'my_name') {
            geoServerURL = `${main_url}Mojani/wms?service=WFS&version=1.1.0&request=GetFeature&typeName=Legislative_Assembly_Boundary&propertyName=${selectedValue}&outputFormat=application/json`;
        } else if (selectedValue === 'village') {
            geoServerURL = `${main_url}Mojani/wms?service=WFS&version=1.1.0&request=GetFeature&typeName=Villages_Boundary&propertyName=${selectedValue}&outputFormat=application/json`;
        }
        
        console.log(geoServerURL, "geoServerURLsearch");
  
        $.getJSON(geoServerURL, function (data) {
          var workTypeSet = new Set();
  
          // Populate the Set with work types
          $.each(data.features, function (index, feature) {
            var workType = feature.properties[selectedValue];
  
            // Convert number (double) values to strings
            if (typeof workType === 'number') {
              workType = workType.toString();
            }
            if (workType !== null) {
              workTypeSet.add(workType);
            }
          });
  
          // Convert the Set to an array
          var uniqueWorkTypes = Array.from(workTypeSet);
          console.log(uniqueWorkTypes, "uniqueWorkTypes");
  
          // Call the callback function with the uniqueWorkTypes array
          callback(uniqueWorkTypes);
        });
      }
  
      // Call getValues function and initialize autocomplete
      getValues(function (data) {
    
        autocomplete(document.getElementById("searchInputDashboard"), data);
      });
    });
  
    // autocomplete function
    function autocomplete(input, arr, selectedColumn) {
        console.log(arr, "autocomplete array"); // Log to check the suggestions

      let currentFocus;
      input.addEventListener("input", function () {
        let list, item, i, val = this.value.toLowerCase(); // Convert input value to lowercase for case-insensitive comparison
        closeAllLists();
        if (!val) return false;
        currentFocus = -1;
        list = document.createElement("ul");
        list.setAttribute("id", "autocomplete-list");
        list.setAttribute("class", "autocomplete-items");
        document.getElementById("autocompleteSuggestions").appendChild(list);
        for (i = 0; i < arr.length; i++) {
          if (arr[i].toLowerCase().includes(val)) { // Check if the suggestion contains the input value
            item = document.createElement("li");
            item.innerHTML = arr[i].replace(new RegExp(val, 'gi'), (match) => `<strong>${match}</strong>`); // Highlight matching letters
            item.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
            
            
            item.addEventListener("click", function () {
              selectedValue = this.getElementsByTagName("input")[0].value; // Store the selected value
              console.log(selectedValue, "ppppppppppppppppp")
  
  
              const searchtypefield = $("#search_type").val();
              console.log(searchtypefield, "ppppppppppppppppp99999999")
            //   let cqlFilter;
  
              let cqlFilter = `${searchtypefield} IN ('${selectedValue}')`;
  
              Legislative_Assembly_Boundary.setParams({
                CQL_FILTER: cqlFilter,
                maxZoom: 19.5,
                // styles: "IWMS_points"
              });
              fitbous1(cqlFilter);
              fitbous(cqlFilter);

  
              input.value = selectedValue;
              closeAllLists();
            });
            list.appendChild(item);
          }
        }
      });
  
      input.addEventListener("keydown", function (e) {
        let x = document.getElementById("autocomplete-list");
        if (x) x = x.getElementsByTagName("li");
        if (e.keyCode === 40) {
          currentFocus++;
          addActive(x);
        } else if (e.keyCode === 38) { //up
          currentFocus--;
          addActive(x);
        } else if (e.keyCode === 13) {
          e.preventDefault();
          if (currentFocus > -1) {
            if (x) {
              selectedValue = x[currentFocus].getElementsByTagName("input")[0].value; // Store the selected value
              input.value = selectedValue;
              closeAllLists();
            }
          }
        }
      });
  
      function addActive(x) {
        if (!x) return false;
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        x[currentFocus].classList.add("autocomplete-active");
      }
  
      function removeActive(x) {
        for (let i = 0; i < x.length; i++) {
          x[i].classList.remove("autocomplete-active");
        }
      }
  
      function closeAllLists(elmnt) {
        let x = document.getElementsByClassName("autocomplete-items");
        for (let i = 0; i < x.length; i++) {
          if (elmnt !== x[i] && elmnt !== input) {
            x[i].parentNode.removeChild(x[i]);
          }
        }
      }
  
      document.addEventListener("click", function (e) {
        closeAllLists(e.target);
      });
    }
  });

// Global variable to keep track of the currently highlighted layer
let currentHighlightedLayer = null;

function fitbous1(filter) {
  var layers = ["Mojani:Legislative_Assembly_Boundary"];
  var bounds = null;
  var highlightStyle = { color: 'blue', weight: 2 }; // Define blue highlight style

  // Clear the previous highlighted layer from the map
  if (currentHighlightedLayer) {
    map.removeLayer(currentHighlightedLayer); // Remove the existing layer
    currentHighlightedLayer = null; // Reset the current highlighted layer
  }

  var processLayer = function (layerName, callback) {
    var urlm = main_url + "ows?service=WFS&version=1.0.0&request=GetFeature&typeName=" +
      layerName + "&CQL_FILTER=" + filter + "&outputFormat=application/json";
    $.getJSON(urlm, function (data) {
      // Create a GeoJSON layer with the highlight style
      currentHighlightedLayer = L.geoJson(data, {
        style: highlightStyle // Apply the highlight style
      }).addTo(map); // Add the layer to the map

      var layerBounds = currentHighlightedLayer.getBounds(); // Get bounds of the highlighted layer
      if (bounds) {
        bounds.extend(layerBounds);
      } else {
        bounds = layerBounds;
      }
      callback();
    });
  };

  var layersProcessed = 0;
  layers.forEach(function (layerName) {
    processLayer(layerName, function () {
      layersProcessed++;
      if (layersProcessed === layers.length) {
        if (bounds) {
          map.fitBounds(bounds); // Fit the map to the bounds of the highlighted layer
        }
      }
    });
  });
}

function fitbous(filter) {
  var layers = ["Mojani:Villages_Boundary"];
  var bounds = null;
  var highlightStyle = { color: 'blue', weight: 2 }; // Define blue highlight style

  // Clear the previous highlighted layer from the map
  if (currentHighlightedLayer) {
    map.removeLayer(currentHighlightedLayer); // Remove the existing layer
    currentHighlightedLayer = null; // Reset the current highlighted layer
  }

  var processLayer = function (layerName, callback) {
    var urlm = main_url + "ows?service=WFS&version=1.0.0&request=GetFeature&typeName=" +
      layerName + "&CQL_FILTER=" + filter + "&outputFormat=application/json";
    $.getJSON(urlm, function (data) {
      // Create a GeoJSON layer with the highlight style
      currentHighlightedLayer = L.geoJson(data, {
        style: highlightStyle // Apply the highlight style
      }).addTo(map); // Add the layer to the map

      var layerBounds = currentHighlightedLayer.getBounds(); // Get bounds of the highlighted layer
      if (bounds) {
        bounds.extend(layerBounds);
      } else {
        bounds = layerBounds;
      }
      callback();
    });
  };

  var layersProcessed = 0;
  layers.forEach(function (layerName) {
    processLayer(layerName, function () {
      layersProcessed++;
      if (layersProcessed === layers.length) {
        if (bounds) {
          map.fitBounds(bounds); // Fit the map to the bounds of the highlighted layer
        }
      }
    });
  });
}


// popup



// const layerDetails = {
//     "Mojani:Legislative_Assembly_Boundary": ["my_name", "mva_name", "my", "mva", "ac_name"], 
// };

// // Create a mapping for user-friendly labels
// const labelMapping = {
//     "my_name": "MahaYuti Candidate Name",
//     "mva_name": "MahaVikasAaghadi Candidate Name",
//     "my": "MahaYuti",
//     "mva": "MahaVikasAaghadi",
//     "ac_name": "District Name"
// };

// map.on("click", async (e) => {
//     let bbox = map.getBounds().toBBoxString();
//     let size = map.getSize();
  
//     for (let layer in layerDetails) {
//         let selectedKeys = layerDetails[layer];
//         let urrr = `https://info.dpzoning.com/geoserver/Mojani/wms?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetFeatureInfo&FORMAT=image%2Fpng&TRANSPARENT=true&QUERY_LAYERS=${layer}&STYLES&LAYERS=${layer}&exceptions=application%2Fvnd.ogc.se_inimage&INFO_FORMAT=application/json&FEATURE_COUNT=50&X=${Math.round(e.containerPoint.x)}&Y=${Math.round(e.containerPoint.y)}&SRS=EPSG%3A4326&WIDTH=${size.x}&HEIGHT=${size.y}&BBOX=${bbox}`;

//         console.log(urrr, "mojanipopupurl");
//         try {
//             let response = await fetch(urrr);
//             let html = await response.json();
  
//             if (html.features.length > 0) { // Check if any features are returned
//                 var htmldata = html.features[0].properties;
//                 let txtk1 = "";
//                 for (let key of selectedKeys) {
//                     // Get the label from the mapping
//                     let label = labelMapping[key];
//                     // Only include rows where the value is not null or undefined
//                     if (htmldata.hasOwnProperty(key) && htmldata[key] != null) {
//                         let value = htmldata[key];
//                         txtk1 += "<tr><td>" + label + "</td><td>" + value + "</td></tr>";
//                     }
//                 }
  
//                 // If there is data to show, create and display the popup
//                 if (txtk1) {
//                     let detaildata1 = "<div style='max-height: 350px; max-height: 250px;'><table style='width:110%;' class='popup-table'>" + txtk1 + "</table></div>";
//                     L.popup().setLatLng(e.latlng).setContent(detaildata1).openOn(map);
//                 } else {
//                     console.log("No valid features found at this location.");
//                 }
//             } else {
//                 console.log("No features found at this location.");
//             }
//         } catch (error) {
//             console.error("Error fetching data:", error);
//         }
//     }
// });



// Load candidate JSON data
let candidateData = [];

fetch("candidate_data.json")
  .then(response => response.json())
  .then(data => {
    candidateData = data;
    console.log(candidateData, "candidsretdata");  // Check candidate data in console
  })
  .catch(error => console.error("Error loading candidate data:", error));

// Popup code
const labelMapping = {
    "my_name": "MahaYuti Candidate Name",
    "mva_name": "MahaVikasAaghadi Candidate Name",
    "my": "MahaYuti",
    "mva": "MahaVikasAaghadi",
    "ac_name": "Constituency Name"
};

// Handle Map Click Event
map.on("click", (e) => {
  // Get the clicked location's constituency name (This can be from the map's data or manually assigned)
  // In this example, I’m assuming the constituency name is provided by the clicked point (e.latlng)
  let constituency = getConstituencyFromLocation(e.latlng);  // You'll need to define how to get this from the clicked location
  
  // Filter candidates based on the clicked constituency
  const candidates = candidateData.filter(candidate => candidate.Constituency.toLowerCase() === constituency.toLowerCase());

  // Construct the popup content
  let txtk1 = "";

  if (candidates.length > 0) {
    txtk1 += `<tr><th colspan='2'>Candidate Count</th></tr>`;
    txtk1 += `<tr><td>Total Candidates</td><td>${candidates.length}</td></tr>`;
    // Loop through the candidates and add their details
    candidates.forEach(candidate => {
      txtk1 += `<tr><td>Name</td><td>${candidate.Name}</td></tr>`;
      txtk1 += `<tr><td>Party</td><td>${candidate.Party}</td></tr>`;
      txtk1 += `<tr><td>Status</td><td>${candidate.Status}</td></tr>`;
      txtk1 += `<tr><td>Reference Link</td><td><a href="${candidate['Reference Link']}" target="_blank">View Profile</a></td></tr>`;
    });
  } else {
    txtk1 += `<tr><td colspan="2">No candidates found for this constituency.</td></tr>`;
  }

  // Display the popup with candidate data
  let detaildata1 = `<div style='max-height: 350px; overflow: auto;'><table style='width:110%;' class='popup-table'>${txtk1}</table><button onclick="viewMore('${constituency}')">View More</button></div>`;
  L.popup().setLatLng(e.latlng).setContent(detaildata1).openOn(map);
});

// Function to get constituency name from the clicked location (You need to define this based on your map data)
function getConstituencyFromLocation(latlng) {
  // This function should return the constituency name based on the clicked latitude and longitude.
  // You may have a predefined mapping of latlng to constituencies, or use a spatial query if you have that data.
  // For now, I’ll just assume the name is returned directly for simplicity.
  
  // Example mapping: (This is just for illustration. Replace with your own logic)
  if (latlng.lat > 20 && latlng.lng < 70) {
    return "Constituency A";  // Replace with your actual mapping logic
  } else if (latlng.lat > 30 && latlng.lng < 80) {
    return "Constituency B";
  }
  // Add other conditions or a method to fetch actual constituency name based on latlng
  return "Unknown Constituency";
}

// Function to handle 'View More' button click
function viewMore(constituency) {
  // Redirect to or open a new page with more details
  // For example, you can open a new URL to show more candidate info based on the constituency
  // window.open(`candidate_details.html?constituency=${constituency}`, "_blank");
}
