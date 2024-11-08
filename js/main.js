const main_url = "https://info.dpzoning.com/geoserver/";

// Live location on page load
map.on('load', function () {
  LiveLocation();  // Call live location when page loads
});
function LiveLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
      var lat = position.coords.latitude;
      var lon = position.coords.longitude;

      console.log("Accurate Latitude: " + lat + ", Longitude: " + lon);

      map.setView([lat, lon], 15); // Center map on live location

      // GeoServer query for constituency name
      var geoServerURL = `${main_url}Mojani/ows?service=WFS&version=1.1.0&request=GetFeature&typeName=Mojani:Legislative_Assembly_Boundary&outputFormat=application/json&cql_filter=INTERSECTS(geometry, POINT(${lon} ${lat}))`;
      console.log("GeoServer URL:", geoServerURL); // Log GeoServer URL

      $.getJSON(geoServerURL, function (data) {
        if (data && data.features && data.features.length > 0) {
          var constituencyName = data.features[0].properties.ac_name; // Adjust field name if needed
          console.log("Constituency Name found:", constituencyName);

          L.marker([lat, lon]).addTo(map)
            .bindPopup(`Current Location:<br>Constituency: ${constituencyName}<br>Lat: ${lat}<br>Long: ${lon}`)
            .openPopup();
        } else {
          console.log("No constituency found for this location.");
          L.marker([lat, lon]).addTo(map)
            .bindPopup(`Current Location:<br>Constituency: Unknown<br>Lat: ${lat}<br>Long: ${lon}`)
            .openPopup();
        }
      }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error("GeoServer request failed:", textStatus, errorThrown);
      });

    }, function (error) {
      alert("Unable to retrieve your location. Error: " + error.message);
      console.error("Geolocation error:", error);
    }, {
      enableHighAccuracy: true,
      timeout: 15000,
      maximumAge: 0
    });
  } else {
    alert("Geolocation is not supported by this browser.");
  }
}


// Trigger live location when the page loads
$(document).ready(function () {
  LiveLocation();
});
// ---------------------------------------------
// search type














// For adding popups
const layerDetails = {
  "Mojani:Legislative_Assembly_Boundary": ["ac_name"],
  "Mojani:Villages_Boundary": ["village"],
};

map.on("click", async (e) => {
  let bbox = map.getBounds().toBBoxString();
  let size = map.getSize();
  let boundaryNames = {};

  // Loop through the layers and populate boundaryNames
  for (let layer in layerDetails) {
    // let selectedKeys = layerDetails[layer];
    let urrr = `https://info.dpzoning.com/geoserver/Mojani/wms?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetFeatureInfo&FORMAT=image%2Fpng&TRANSPARENT=true&QUERY_LAYERS=${layer}&STYLES&LAYERS=${layer}&exceptions=application%2Fvnd.ogc.se_inimage&INFO_FORMAT=application/json&FEATURE_COUNT=50&X=${Math.round(e.containerPoint.x)}&Y=${Math.round(e.containerPoint.y)}&SRS=EPSG%3A4326&WIDTH=${size.x}&HEIGHT=${size.y}&BBOX=${bbox}`;

    try {
      let response = await fetch(urrr);
      let html = await response.json();
      let htmldata = html.features[0].properties;

      if (layer === "Mojani:Legislative_Assembly_Boundary") {
        boundaryNames['Constituency :'] = htmldata.ac_name;
        boundaryNames['DistrictName'] = htmldata.dist_name
      }
      if (layer === "Mojani:Villages_Boundary") {
        boundaryNames['Village Name :'] = htmldata.village;
      }

    } catch (error) {
      console.error("Error fetching data:", error);
    }
  }

  let constiName = boundaryNames['Constituency :'].toUpperCase();
  let DistictName = boundaryNames['DistrictName']
  $.ajax({
    type: "POST",
    url: "APIS/get_candidate_count.php",
    contentType: "application/json",
    data: JSON.stringify({ "constituency": constiName }),
    success: function (data) {
      console.log(typeof data, "jjjjjjjjjjjjjjjj")
      if (typeof data === "string") {
        try {
          data = JSON.parse(data);  // Parse to JSON if it's a string
          console.log(data, "lllllllllllllll")
        } catch (parseError) {
          console.error("Failed to parse API response:", parseError);
        }
      }
      // Verify if the response contains "count" and assign it to boundaryNames
      if (data) {
        // alert(data)
        boundaryNames['Total Candidate   :'] = data.count;
        console.log(data.count)
      }

      // Rebuild and display popup after updating count
      let txtk1 = "<table>";
      for (let key in boundaryNames) {
        if (boundaryNames.hasOwnProperty(key)) {
            // Skip the iteration if the key is 'district'
            if (key === 'DistrictName') {
                continue;
            }
            let value = boundaryNames[key];
            txtk1 += `<tr><td>${key}</td><td>${value}</td></tr>`;
        }
    }
      txtk1 += "</table>";

      // const constituesncyName = boundaryNames['Constituency :'];
      const candidatesCount = boundaryNames['Total Candidate  :']= data.count;
      // console.log(constituesncyName, candidatesCount, "popopopopopopo");

      let detaildata1 = `<div style='max-height: 350px; max-height: 250px;'>
                    <table style='width:110%;' class='popup-table'>
                      ${txtk1}
                      <tr>
                        <td colspan="2" style="text-align: center;">
                          <button class="bottonView" onclick="seeAllCandidates(${e.latlng.lat}, ${e.latlng.lng}, '${constiName}', ${candidatesCount},'${DistictName}')">See all Candidates</button>
                        </td>
                      </tr>
                    </table>
                   </div>`;

      L.popup().setLatLng(e.latlng).setContent(detaildata1).openOn(map);


    },
    error: function (xhr, status, error) {
      console.error("Failed to fetch data from API:", error);
    }
  });

});



function seeAllCandidates(lat, lng, c, n, district) {
  $.ajax({
    type: "POST",
    url: "APIS/getCandidateDetails.php",
    contentType: "application/json",
    data: JSON.stringify({ "constituency": c }),
    success: function (data) {
      console.log("Response Data:", data);

      // Check if data is an array of candidates
      if (Array.isArray(data)) {
        // Start the card structure
        let candidateCardsHtml = `
          <div class="candidate-container" style="display: flex; flex-wrap: wrap; gap: 10px; max-height: 41vh; overflow-y: auto;">
            <div class="summary-card" style="width: 100%;">
              <p><strong>Constituency:</strong> ${c}</p>
              <p ><strong style="margin: 0;">Total Candidates:</strong> ${n}</p>
            </div>`;

        // Loop through each candidate and create a card with side-by-side layout
        data.forEach(candidate => {
          candidateCardsHtml += `
            <div class="candidate-card" data-name="${candidate.name}" data-party="${candidate.party}" data-image="${candidate.image_path}" data-lat="${lat}" data-lng="${lng}"
              style="display: flex; border: 1px solid #ccc; padding: 10px; border-radius: 8px; cursor: pointer; width:200px; font-size: 10px; background: #bfdbfe;">
              
              <!-- Image on the left -->
              <img src="${candidate.image_path}" alt="${candidate.name}" style="width: 60px; height: 60px; border-radius: 20%; margin-right: 5px; margin-top:6px;">
              
              <!-- Details on the right -->
              <div style="flex: 1;">
                <p><strong>${candidate.name}</strong></p>
               <p style="margin: 0;">Party: ${candidate.party}</p>
  <p style="margin: 0;">District: ${district}</p>
              </div>
            </div>`;
        });

        candidateCardsHtml += `</div>`;

        L.popup()
          .setLatLng([lat, lng])
          .setContent(candidateCardsHtml)
          .openOn(map);

        $('.candidate-card').on('click', function () {
          const candidateName = $(this).data('name');
          const candidateParty = $(this).data('party');
          const candidateImage = $(this).data('image');
          const popupLat = $(this).data('lat');
          const popupLng = $(this).data('lng');
          $.ajax({
            type: "POST",
            url: "APIS/getPersonalDetails.php",
            contentType: "application/json",
            data: JSON.stringify({
              "name": candidateName.toUpperCase(),
              "constituency": c
            }),
            success: function (data) {
              console.log("Response Data:", data);
              try {
                data = JSON.parse(data);
              } catch (parseError) {
                console.error("Failed to parse API response:", parseError);
              }

              const additionalInfo = JSON.parse(data.data.additional_info);
              const personalInfo = additionalInfo["Personal Information"];
              const lines = personalInfo.trim().split('\n');
              const jsonData = {};
              let currentKey = null;
              lines.forEach(line => {
                if (line.endsWith(':')) {
                  currentKey = line.slice(0, -1).trim();
                  jsonData[currentKey] = '';
                } else if (currentKey) {
                  jsonData[currentKey] += line.trim() + ' ';
                }
              });
              Object.keys(jsonData).forEach(key => {
                jsonData[key] = jsonData[key].trim();
              });

              let additionalInfoHtml = '';
              for (const [key, value] of Object.entries(jsonData)) {
                additionalInfoHtml += `<p><strong>${key}:</strong> ${value}</p>`;
              }

              const candidateDetailsHtml = `
                <div style= "align-items: center; text-align: center; width :250px; padding:5px;">
                  <img src="${candidateImage}" alt="${candidateName}" style="width: 100px; border:2px solid #bbb; height: 100px;border-radius:50%; ">
                  <div style= "font-size:10px; width:250px; text-align: left">
                    <h3 style = "color : blue; text-align:center;">${candidateName}</h3>
                   <hr>
      
                    <p><strong>Status:</strong> ${data.data.status} &nbsp;&nbsp;&nbsp;&nbsp;   <strong>Constituency:</strong> ${data.data.constituency}</p>
                    <hr>
                    <p><strong>State:</strong> ${data.data.state}  &nbsp;&nbsp;&nbsp;&nbsp; <strong>District:</strong> ${district}  </p>
                    <hr>
                    ${additionalInfoHtml}
                    <hr>
                    <p><a href="${data.data.reference_link}" target="_blank">More Details</a></p>
                  </div>
                </div>`;

              L.popup()
                .setLatLng([popupLat, popupLng])
                .setContent(candidateDetailsHtml)
                .openOn(map);
            }
          });
        });
      } else {
        console.error("Unexpected data format:", data);
        L.popup()
          .setLatLng([lat, lng])
          .setContent("<p>No candidates found.</p>")
          .openOn(map);
      }
    },
    error: function (xhr, status, error) {
      console.error("Failed to fetch data from API:", error);
      L.popup()
        .setLatLng([lat, lng])
        .setContent("<p>Error loading candidate details.</p>")
        .openOn(map);
    }
  });
}


// function seeAllCandidates(lat, lng, c, n,district) {
//   $.ajax({
//     type: "POST",
//     url: "APIS/getCandidateDetails.php",
//     contentType: "application/json",
//     data: JSON.stringify({ "constituency": c }),
//     success: function (data) {
//       console.log("Response Data:", data);  // Log the full response for inspection

//       // Check if data is an array of candidates
//       if (Array.isArray(data)) {
//         // Start the table structure
//         let candidateTableHtml = `
         
//           <table class="candtable" border="1">

//           <tr>
//               <th>Constituency</th>
//               <th>${c}</th>
//             </tr>
//             <tr>
//               <th>Total Candidates</th>
//               <th>${n}</th>
//             </tr>
//             <tr>
//               <th>Image</th>
//               <th>Name</th>
//               <th>Party</th>
//               <th>District</th>
//             </tr>`;

//         // Loop through each candidate and add a row to the table
//         data.forEach(candidate => {
//           candidateTableHtml += `
//             <tr class="candidate-row" data-name="${candidate.name}" data-party="${candidate.party}" data-image="${candidate.image_path}" data-lat="${lat}" data-lng="${lng}">
//               <td><img src="${candidate.image_path}" alt="${candidate.name}" style="width: 50px; height: 50px;"></td>
//               <td>${candidate.name}</td>
//               <td>${candidate.party}</td>
//               <td>${district}</td>
//             </tr>`;
//         });

//         candidateTableHtml += `</table>`;
//         L.popup()
//           .setLatLng([lat, lng])  // Use the same coordinates from the click event
//           .setContent(`<div style='max-height: 350px; overflow-y: auto;'>${candidateTableHtml}</div>`)
//           .openOn(map);

//         $('.candidate-row').on('click', function () {
//           const candidateName = $(this).data('name');
//           const candidateParty = $(this).data('party');
//           const candidateImage = $(this).data('image');
//           const popupLat = $(this).data('lat');
//           const popupLng = $(this).data('lng');
//           $.ajax({
//             type: "POST",
//             url: "APIS/getPersonalDetails.php",
//             contentType: "application/json",
//             data: JSON.stringify({
//               "name": candidateName.toUpperCase(),
//               "constituency": c
//             }),
//             success: function (data) {

//               console.log(typeof data, "jjjjjjjjjjjjjjjj")
//               try {
//                 data = JSON.parse(data);  // Parse to JSON if it's a string
//                 console.log(data, "lllllllllllllll")
//               } catch (parseError) {
//                 console.error("Failed to parse API response:", parseError);
//               }

//               console.log("Response Datarrrrrrrrrrrrrrrrrrrrrrrr:", data);
//               const additionalInfo = JSON.parse(data.data.additional_info);
//               // Extract the "Personal Information" field
//               const personalInfo = additionalInfo["Personal Information"];
//               const lines = personalInfo.trim().split('\n');
//               const jsonData = {};
//               let currentKey = null;
//               lines.forEach(line => {
//                 if (line.endsWith(':')) {
//                   currentKey = line.slice(0, -1).trim();
//                   jsonData[currentKey] = '';
//                 } else if (currentKey) {
//                   jsonData[currentKey] += line.trim() + ' ';
//                 }
//               });
//               Object.keys(jsonData).forEach(key => {
//                 jsonData[key] = jsonData[key].trim();
//               });

//               let AdditionalInfo = JSON.stringify(jsonData, null, 2);
//               console.log("infoLines", AdditionalInfo);

//               // Creating HTML table rows dynamically from jsonData
//               let additionalInfoRows = '';

//               // Iterate over the keys in jsonData and create table rows
//               for (const [key, value] of Object.entries(jsonData)) {
//                 additionalInfoRows += `
//                               <tr>
//                                   <td>${key}</td>
//                                   <td>${value}</td>
//                               </tr>`;
//                                         }
//                             const candidateDetailsHtml = `
//                               <h3>${candidateName}</h3>
//                               <img src="${candidateImage}" alt="${candidateName}" style="width: 100px; height: 100px;">
//                               <table>
                      
//                                   <tr>
//                                       <td>Status</td>
//                                       <td>${data.data.status}</td>
//                                   </tr>
                                  
//                                   <tr>
//                                       <td>State</td>
//                                       <td>${data.data.state}</td>
//                                   </tr>
//                                   <tr>
//                                       <td>District</td>
//                                       <td>${district}</td>
//                                   </tr>
//                                   <tr>
//                                       <td>Constituency</td>
//                                       <td>${data.data.constituency}</td>
//                                   </tr>
//                                   ${additionalInfoRows}

//                                   <tr>
                                  
//                                     <td>
//                                         <a href="${data.data.reference_link}" target="_blank">More Details</a>
//                                     </td>
//                                   </tr>
//                               </table>
//                           `;

//               // console.log(candidateDetailsHtml);  // This will display the HTML output


//               L.popup()
//                 .setLatLng([popupLat, popupLng])  // Use the same coordinates
//                 .setContent(candidateDetailsHtml)
//                 .openOn(map);

//             }

//           })

//         });
//       } else {
//         console.error("Unexpected data format:", data);
//         L.popup()
//           .setLatLng([lat, lng])
//           .setContent("<p>No candidates found.</p>")
//           .openOn(map);
//       }
//     },
//     error: function (xhr, status, error) {
//       console.error("Failed to fetch data from API:", error);
//       L.popup()
//         .setLatLng([lat, lng])  // Open the popup on the same coordinates
//         .setContent("<p>Error loading candidate details.</p>")
//         .openOn(map);
//     }
//   });
// }






// kml


















// {/* <p>Party: ${candidateParty}</p> */}


















document.addEventListener('DOMContentLoaded', (event) => {
  var columns = { "ac_name": "Constituency name", "village": "Village Name", "my_name": "Candidate Name" };
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
  let selectedValue;

  $("#search_type").change(function () {
    var selectedValue = $(this).val();
    var selectedText = columns[selectedValue]; // Get corresponding label from columns object
    var input = document.getElementById("searchInputDashboard");
    // Update input placeholder and clear input value
    var selectedValue = select.value;
    input.placeholder = "Search " + selectedText;
    input.value = "";

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




// Function to show candidate list at the bottom of the screen
function viewMore(constituency) {
  // Show the candidate list div
  document.getElementById('candidateListWrapper').style.display = 'block';

  // Scroll the page to the bottom to reveal the candidate list div
  window.scrollTo({
    top: document.body.scrollHeight,
    behavior: 'smooth'
  });

  // Close the popup on the map after clicking "See all candidates"
  if (mapPopup) {
    map.closePopup();
  }
}

// Function to close the candidate list
function closeCandidateList() {
  document.getElementById('candidateListWrapper').style.display = 'none';

  // Allow normal scrolling again after closing the candidate list
  document.body.style.overflow = 'auto';
}

function getConstituencyFromLocation(latlng) {

  if (latlng.lat > 20 && latlng.lng < 70) {
    return "Constituency A";  // Replace with your actual mapping logic
  } else if (latlng.lat > 30 && latlng.lng < 80) {
    return "Constituency B"
  }
  // Add other conditions or a method to fetch actual constituency name based on latlng
  return "Unknown Constituency";
}

// Function to handle 'View More' button click
function viewMore(constituency) {

}

function showCandidatesDiv() {
  const candidatesDiv = document.getElementById("candidatesDiv");
  const mapContainer = document.getElementById("map");

  // Display the candidatesDiv and set its position
  candidatesDiv.style.display = "block";
  candidatesDiv.style.position = "fixed";
  candidatesDiv.style.bottom = "176px";
  candidatesDiv.style.width = "83%";
  candidatesDiv.style.zIndex = "1000";

  // Adjust the margin of mapContainer to move it above the candidatesDiv
  mapContainer.style.marginBottom = "176px"; // Adjust this to the height of candidatesDiv
}

// JavaScript code to hide the candidatesDiv on cancel icon click
document.getElementById("cancel01Icon").addEventListener("click", function () {
  const candidatesDiv = document.getElementById("candidatesDiv");
  const mapContainer = document.getElementById("map");

  // Hide the candidatesDiv and reset mapContainer margin
  candidatesDiv.style.display = "none";
  mapContainer.style.marginBottom = "10vh"; // Reset the map's margin
});

// Automatically show candidatesDiv when the "See all candidates" button is clicked in the popup
document.querySelector(".popup-button").addEventListener("click", function () {

  const map = window.map; // Assuming you have access to your map object
  if (map) {
    map.closePopup(); // This will close any active popup
  }

  // Hide the popup content (optional if you want to hide content explicitly)
  const popup = document.querySelector(".leaflet-popup-content-wrapper");
  if (popup) {
    popup.style.display = "none";  // Optionally hide the popup content
  }

  showCandidatesDiv();
});















