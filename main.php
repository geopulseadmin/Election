<?php
session_start();
if (!isset($_SESSION['from_index']) || $_SESSION['from_index'] !== true) {
    header('Location: index.php');
    exit();
}
// Clear the session variable after validating
unset($_SESSION['from_index']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Election</title>

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/leafletcss.css">

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
      <!-- Leaflet CSS -->
      <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body style="overflow: hidden;">
      <div class="maindiv">
            <div class="header">
                  <div class="title-container">
                        <div class="icon">
                              <img src="image/Frame 14101261702.svg" alt="">
                        </div>
                        <div class="search-container">
                              <div class="search-box">
                                    <div class="SearchBar search-container" id="search-container">
                                          <select class="search-icon" aria-label="" id="search_type">
                                                <option value="">Select Type</option>
                                          </select>
                                          <input class="form-contro ms-2" type="search" placeholder="Search"
                                                aria-label="Search" id="searchInputDashboard" />
                                          <div id="autocompleteSuggestions" class="autocomplete-suggestions"></div>

                                    </div>
                              </div>

                              <div class="filter-box">
                                    <div class="filter-icon location-icon" onclick="LiveLocation()">
                                          <img src="image/mage_location-fill.svg" alt="live location">
                                    </div>
                              </div>

                              <div class="filter">
                                    <div class="filter-icon location-icon" onclick="LiveLocation()">
                                          <img src="image/menu-03.png" alt="live location">
                                    </div>
                              </div>
                        </div>


                  </div>

            </div>
            <div id="map"></div>
      </div>

      <div class="container">
            <div class="info-text">
                  This web application is an informational resource and not affiliated with any government agency
            </div>
            <div class="divider-container">
                  <div class="divider"></div>
            </div>
      </div>

      <!-- <div id="candidatesDiv" class="frame-parent" style="display: none;">
            <div class="frame-group">
                  <div class="frame-container">
                        <div class="constituency-parent">
                              <div class="constituency">Constituency:</div>
                              <div class="kannad">Kannad</div>
                        </div>
                        <div class="constituency-parent">
                              <div class="constituency">Total Candidates:</div>
                              <div class="kannad">12</div>
                        </div>
                  </div>
                  <img class="cancel-01-icon" alt="" src="image/cancel-01 (2).png" id="cancel01Icon">
            </div>
            <div class="frame-div">
                  <div class="frame-parent1">
                        <div class="frame-parent2" id="frameContainer">
                              <div class="picture-wrapper">
                                    <div class="picture">PICTURE</div>
                              </div>
                              <div class="aditya-santosh-pawar-parent">
                                    <div class="aditya-santosh-pawar">Aditya Santosh Pawar</div>
                                    <div class="frame-parent3">
                                          <div class="party-parent">
                                                <div class="party">Party:</div>
                                                <div class="bhartiya-janata-party">Bhartiya Janata Party</div>
                                          </div>
                                          <div class="party-parent">
                                                <div class="party">District:</div>
                                                <div class="bhartiya-janata-party">Pune</div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="frame-parent2" id="frameContainer1">
                              <div class="picture-wrapper">
                                    <div class="picture">PICTURE</div>
                              </div>
                              <div class="aditya-santosh-pawar-parent">
                                    <div class="aditya-santosh-pawar">Aditya Santosh Pawar</div>
                                    <div class="frame-parent3">
                                          <div class="party-parent">
                                                <div class="party">Party:</div>
                                                <div class="bhartiya-janata-party">Bhartiya Janata Party</div>
                                          </div>
                                          <div class="party-parent">
                                                <div class="party">District:</div>
                                                <div class="bhartiya-janata-party">Pune</div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="frame-parent2" id="frameContainer2">
                              <div class="picture-wrapper">
                                    <div class="picture">PICTURE</div>
                              </div>
                              <div class="aditya-santosh-pawar-parent">
                                    <div class="aditya-santosh-pawar">Aditya Santosh Pawar</div>
                                    <div class="frame-parent3">
                                          <div class="party-parent">
                                                <div class="party">Party:</div>
                                                <div class="bhartiya-janata-party">Bhartiya Janata Party</div>
                                          </div>
                                          <div class="party-parent">
                                                <div class="party">District:</div>
                                                <div class="bhartiya-janata-party">Pune</div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="frame-parent2" id="frameContainer3">
                              <div class="picture-wrapper">
                                    <div class="picture">PICTURE</div>
                              </div>
                              <div class="aditya-santosh-pawar-parent">
                                    <div class="aditya-santosh-pawar">Aditya Santosh Pawar</div>
                                    <div class="frame-parent3">
                                          <div class="party-parent">
                                                <div class="party">Party:</div>
                                                <div class="bhartiya-janata-party">Bhartiya Janata Party</div>
                                          </div>
                                          <div class="party-parent">
                                                <div class="party">District:</div>
                                                <div class="bhartiya-janata-party">Pune</div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="frame-parent2" id="frameContainer4">
                              <div class="picture-wrapper">
                                    <div class="picture">PICTURE</div>
                              </div>
                              <div class="aditya-santosh-pawar-parent">
                                    <div class="aditya-santosh-pawar">Aditya Santosh Pawar</div>
                                    <div class="frame-parent3">
                                          <div class="party-parent">
                                                <div class="party">Party:</div>
                                                <div class="bhartiya-janata-party">Bhartiya Janata Party</div>
                                          </div>
                                          <div class="party-parent">
                                                <div class="party">District:</div>
                                                <div class="bhartiya-janata-party">Pune</div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="frame-parent2" id="frameContainer5">
                              <div class="picture-wrapper">
                                    <div class="picture">PICTURE</div>
                              </div>
                              <div class="aditya-santosh-pawar-parent">
                                    <div class="aditya-santosh-pawar">Aditya Santosh Pawar</div>
                                    <div class="frame-parent3">
                                          <div class="party-parent">
                                                <div class="party">Party:</div>
                                                <div class="bhartiya-janata-party">Bhartiya Janata Party</div>
                                          </div>
                                          <div class="party-parent">
                                                <div class="party">District:</div>
                                                <div class="bhartiya-janata-party">Pune</div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="frame-parent2" id="frameContainer6">
                              <div class="picture-wrapper">
                                    <div class="picture">PICTURE</div>
                              </div>
                              <div class="aditya-santosh-pawar-parent">
                                    <div class="aditya-santosh-pawar">Aditya Santosh Pawar</div>
                                    <div class="frame-parent3">
                                          <div class="party-parent">
                                                <div class="party">Party:</div>
                                                <div class="bhartiya-janata-party">Bhartiya Janata Party</div>
                                          </div>
                                          <div class="party-parent">
                                                <div class="party">District:</div>
                                                <div class="bhartiya-janata-party">Pune</div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="frame-parent2" id="frameContainer7">
                              <div class="picture-wrapper">
                                    <div class="picture">PICTURE</div>
                              </div>
                              <div class="aditya-santosh-pawar-parent">
                                    <div class="aditya-santosh-pawar">Aditya Santosh Pawar</div>
                                    <div class="frame-parent3">
                                          <div class="party-parent">
                                                <div class="party">Party:</div>
                                                <div class="bhartiya-janata-party">Bhartiya Janata Party</div>
                                          </div>
                                          <div class="party-parent">
                                                <div class="party">District:</div>
                                                <div class="bhartiya-janata-party">Pune</div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="frame-parent2" id="frameContainer8">
                              <div class="picture-wrapper">
                                    <div class="picture">PICTURE</div>
                              </div>
                              <div class="aditya-santosh-pawar-parent">
                                    <div class="aditya-santosh-pawar">Aditya Santosh Pawar</div>
                                    <div class="frame-parent3">
                                          <div class="party-parent">
                                                <div class="party">Party:</div>
                                                <div class="bhartiya-janata-party">Bhartiya Janata Party</div>
                                          </div>
                                          <div class="party-parent">
                                                <div class="party">District:</div>
                                                <div class="bhartiya-janata-party">Pune</div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="frame-wrapper">
                        <div class="frame-child">
                        </div>
                  </div>
            </div>
      </div>

      <div class="profile" id="profileSection" style="">
            <img class="frame-child2" alt="" src="image/Profile.png">

            <div class="candidate-details">Candidate Details</div>
            <div class="frame-item">
            </div>
            <div class="frame-group1">
                  <div class="frame-container">
                        <div class="name-parent">
                              <div class="name">Name</div>
                              <div class="div">नाव</div>
                        </div>
                        <div class="name-of-the-candidate-parent">
                              <div class="name">Name of the candidate</div>
                              <div class="div">उमेदवाराचे नाव</div>
                        </div>
                  </div>
                  <div class="frame-inner">
                  </div>
                  <div class="frame-container">
                        <div class="name-parent">
                              <div class="name">Gender</div>
                              <div class="div">लिंग</div>
                        </div>
                        <div class="name-of-the-candidate-parent">
                              <div class="name">Male</div>
                              <div class="div">पुरुष</div>
                        </div>
                  </div>
                  <div class="frame-inner">
                  </div>
                  <div class="frame-container">
                        <div class="name-parent">
                              <div class="name">Age</div>
                              <div class="div">वय</div>
                        </div>
                        <div class="name">48</div>
                  </div>
                  <div class="frame-inner">
                  </div>
                  <div class="frame-container">
                        <div class="name-parent">
                              <div class="name">Party</div>
                              <div class="div">पार्टी</div>
                        </div>
                        <div class="name-of-the-candidate-parent">
                              <div class="name">Bharti Janta Party</div>
                              <div class="div">भारतीय जनता पार्टी</div>
                        </div>
                  </div>
                  <div class="frame-inner">
                  </div>
                  <div class="frame-parent30">
                        <div class="name-parent">
                              <div class="name">Address</div>
                              <div class="div">पत्ता</div>
                        </div>
                        <div class="name-of-the-candidate-parent">
                              <div class="address-line-1-container">
                                    <p class="p">Address line 1</p>
                                    <p class="p">Address line 2</p>
                              </div>
                              <div class="div9">
                                    <p class="p">पूर्ण पत्ता ओळ 1</p>
                                    <p class="p">पूर्ण पत्ता ओळ 2</p>
                              </div>
                        </div>
                  </div>
                  <div class="frame-inner">
                  </div>
                  <div class="frame-container">
                        <div class="name-parent">
                              <div class="name">Constituency</div>
                              <div class="div">मतदारसंघ</div>
                        </div>
                        <div class="name-of-the-candidate-parent">
                              <div class="name">Name of Const.</div>
                              <div class="div">मतदारसंघाचे नाव</div>
                        </div>
                  </div>
                  <div class="frame-inner">
                  </div>
                  <div class="frame-container">
                        <div class="name-parent">
                              <div class="name">District</div>
                              <div class="div">जिल्हा</div>
                        </div>
                        <div class="name-of-the-candidate-parent">
                              <div class="name">Pune, Maharashtra</div>
                              <div class="div">पुणे, महाराष्ट्र</div>
                        </div>
                  </div>
            </div>
            <img class="cancel-01-icon" alt="" src="image/cancel-01 (2).png" id="cancel01Icon">

      </div> -->


    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="js/layer.js"></script>
    <script src="js/main.js"></script>
     <!-- <script src="js/demo.js"></script> -->

</body>

</html>