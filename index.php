<?php include('scripts/function.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GAZETTEER</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/css/bootstrap.min.css" integrity="sha512-rt/SrQ4UNIaGfDyEXZtNcyWvQeOq0QLygHluFQcSjaGB04IxWhal71tKuzP6K8eYXYB6vJV4pHkXcmFGGQ1/0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@3.3.0/dist/maplibre-gl.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/easy-button.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/weather-icons/2.0.9/css/weather-icons.min.css">
    <link rel="stylesheet" href="assets/css/main.css?v=32423">

</head>
<body>
   
<div class="loading">Loading&#8230;</div>
<form action="">
    <div id="selectContainer"> 
        <select name="" class="form-control countrySelect" id="selectedCountry">
        <?php
            foreach ($countries as $country) {
                $isoCode = $country['iso_a2'];
                $countryName = $country['name'];
                echo "<option value=\"$isoCode\" data-name='$countryName'>$countryName</option>";
            }
        ?>
        </select>
        
    </div>
</form> 
    
<div id="map"></div> 
        



    <div class="modal fade" id="countryInfoModal" tabindex="-1" role="dialog" aria-labelledby="countryInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content"> 
                <div class="modal-header bg-success bg-gradient text-white">
                <h5 class="modal-title">Overview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                <div class="topDetails">
                    <img id="country-flag" src=""><br>
                    <h5>
                        <span id="general-info-title"></span>
                    </h5>
                </div>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>Region</td>
                                <td><span id="region"></span></td>
                            </tr>
                            <tr>
                                <td>Sub Region</td>
                                <td><span id="sub-region"></span></td>
                            </tr>
                            <tr>
                                <td>Time Zone</td>
                                <td><span id="timezones"></span></td>
                            </tr>
                            <tr>
                                <td>Capital city</td>
                                <td><span id="capital-city"></span></td>
                            </tr>
                            <tr>
                                <td>Lanugages spoken</td>
                                <td><span id="languages-spoken"></span></td>
                            </tr>
                            <tr>
                                <td>Currency</td>
                                <td><span id="currencies"></span></td>
                            </tr>
                            <tr>
                                <td>Currency Exchange Rate</td>
                                <td><span id="currenciesExchangeRate"></span></td>
                            </tr>
                            <tr>
                                <td>Population</td>
                                <td><span id="population"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="currencyInfoModal" tabindex="-1" role="dialog" aria-labelledby="currencyInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success bg-gradient text-white">
        <h5 class="modal-title">Currency Exchange</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label id="currency"></label>
          <input type="number" class="form-control" id="currencyValue" placeholder="Enter USD" required>
        </div>
        <table class="table table-striped">
          <tbody>
            <tr>     
            <label>USD</label>
              <td text-align="left" id="currencyExchangeRate"></td>
            </tr>
            <tr>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


 
 

    <div class="modal fade" id="wikiModal" tabindex="-1" role="dialog" aria-labelledby="wikiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success bg-gradient text-white">
                    <h5 class="modal-title" id="wikiModalLabel">Wikipedia Information</h5> 
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <!-- Wikipedia information will be displayed here -->
                    <div id="wiki-info"></div>
                </div> 
            </div>
        </div>
    </div>

    

    <div class="modal fade" id="weatherModal" tabindex="-1" role="dialog" aria-labelledby="weatherModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success bg-gradient text-white">
                    <h5 class="modal-title" id="weatherModalLabel">Weather Information</h5> 
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <!-- Weather information will be displayed here -->
                    <div id="weather-info">
 
                    </div>
                </div> 
            </div>
        </div>
    </div>


    <div class="modal fade" id="newsModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success bg-gradient text-white">
                    <h5 class="modal-title" id="newsModalLabel">News</h5> 
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <!-- Wikipedia information will be displayed here -->
                    <div id="news-info"></div>
                </div> 
            </div>
        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js" integrity="sha512-TPh2Oxlg1zp+kz3nFA0C5vVC6leG/6mm1z9+mA81MI5eaUVqasPLO8Cuk4gMF4gUfP5etR73rgU/8PNMsSesoQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js" integrity="sha512-7rusk8kGPFynZWu26OKbTeI+QPoYchtxsmPeBqkHIEXJxeun4yJ4ISYe7C6sz9wdxeE1Gk3VxsIWgCZTc+vX3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-extra-markers/1.2.1/js/leaflet.extra-markers.js"></script>
    <script src="https://unpkg.com/maplibre-gl@3.3.0/dist/maplibre-gl.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/js/all.min.js" integrity="sha512-LW9+kKj/cBGHqnI4ok24dUWNR/e8sUD8RLzak1mNw5Ja2JYCmTXJTF5VpgFSw+VoBfpMvPScCo2DnKTIUjrzYw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/leaflet-providers@1.13.0/leaflet-providers.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <script src="assets/js/easy-button.js"></script>
    <script src="assets/js/main.js"></script>


  <script>
      
     $(document).ready(function () {

        $('.btn-close').on('click', function () { 
            var modal = $(this).closest('.modal');
 
            modal.modal('hide');
        });

    const accessToken = 'mp7HpF6CTTab9XpRyyepuWcxb0csyu4kTS74Jx2iLGHnM7RsR2kkmnEz3vWCLjRq';
    let tileLayer;
    let userLocationMarker;
    let countryBordersLayer;
    let selectedCountryFeature; 

    const streets = L.tileLayer.provider('Esri.WorldStreetMap');
    const satellite = L.tileLayer.provider('Esri.WorldImagery');

    var basemaps = {
    "Streets": streets,
    "Satellite": satellite
    };

    const map = L.map("map", {
    layers: [streets]
    }).setView([54.5, -4], 6);

    var layerControl = L.control.layers(basemaps).addTo(map);
        
    // Function to fetch the user's current location and display their country's border
    function showUserLocationAndBorder() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            // Reverse geocoding to get the user's country name
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`)
                .then(response => response.json())
                .then(data => {
                    const isoCode = data.address.country_code.toUpperCase();
                    displayCountryBorders(isoCode);

                    // Call the function to display the user's location on the map
                    showUserLocation(position);
                })
                .catch(error => console.error("Error fetching location data:", error));
        });
    } else {
        alert("Geolocation is not supported by your browser.");
    }
    }

    // Call the function to get the user's location and display their country's border on page load
    showUserLocationAndBorder();
    function addMarkerToMap(latitude, longitude, countryName) {
            const marker = L.marker([latitude, longitude]).addTo(map);
            marker.bindPopup(`<b>${countryName}</b>`).openPopup();
        }


  

            async function getAirportsData(latitude, longitude) {
            let params = new URLSearchParams({
                api_key: "ecc350b9-79f6-43db-83ce-997c5643afe3",    
            });
            await fetch(`https://airlabs.co/api/v9/nearby?lat=${latitude}&lng=${longitude}&distance=20&api_key=ecc350b9-79f6-43db-83ce-997c5643afe3`)
                .then((res) => res.json())
                .then((data) => {
                  let airports = data.response.airports;
                  const markerIcon = L.icon({
                    iconUrl: "https://cdn-icons-png.flaticon.com/128/1350/1350222.png",
                    iconSize: [38, 38]
                });
                  airports.forEach((airport) => {
                    const marker = L.marker([airport.lat, airport.lng], {
                      icon: markerIcon
                    }).addTo(map);
                    marker.bindPopup(`<b>${airport.name}</b>`).openPopup();
                  });
                });
            }
        




    // Function to display the user's location on the map and hide the loader
    function showUserLocation(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        if (userLocationMarker) {
            map.removeLayer(userLocationMarker); // Remove the previous marker if it exists
        }
        
        userLocationMarker = L.marker([latitude, longitude]).addTo(map);
        addMarkerToMap(position.coords.latitude, position.coords.longitude, "Your Location");
       

// Reverse geocoding to get the country name
    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`)
    .then(response => response.json())
    .then(data => {
        const countryName = data.address.country;

        // Update the select element to preselect the user's country
        const selectCountry = document.getElementById('selectedCountry');
        const isoCode = data.address.country_code.toUpperCase();

        for (const option of selectCountry.options) {
            if (option.value === isoCode) {
                option.selected = true;
                break;
            }
        }

        userLocationMarker.bindPopup(`You are in ${countryName}`).openPopup();

        // Hide the loading element
        const loadingElement = document.querySelector('.loading');
        loadingElement.style.display = 'none';

        // Set the map location to the user's location
        map.setView([latitude, longitude], 10);

        // Add the map layer
        if (!tileLayer) {
            tileLayer = L.tileLayer(
                `https://tile.jawg.io/jawg-streets/{z}/{x}/{y}.png?access-token=${accessToken}`, {
                    attribution: '<a href="http://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank" class="jawg-attrib">&copy; <b>Jawg</b>Maps</a> | <a href="https://www.openstreetmap.org/copyright" title="OpenStreetMap is open data licensed under ODbL" target="_blank" class="osm-attrib">&copy; OSM contributors</a>',
                    maxZoom: 50
                }
            ).addTo(map);
        }

        // Call the correct function to display the user's location on the map
        addMarkerToMap(latitude, longitude, countryName);
    })
    .catch(error => console.error("Error fetching location data:", error));

    getAirportsData(latitude, longitude);

    }


    // Load the GeoJSON data
    function displayCountryBorders(selectedIsoCode) {
        if (countryBordersLayer) {
            map.removeLayer(countryBordersLayer); // Remove the previous borders if they exist
        }
        // Load the GeoJSON data for the selected country
        fetch('countryBorders.geo.json')
            .then(response => response.json())
            .then(data => {
                // Filter the features to find the selected country by ISO code
                const selectedCountryFeature = data.features.find(feature => feature.properties.iso_a2 === selectedIsoCode);

                if (selectedCountryFeature) {
                    countryBordersLayer = L.geoJSON(selectedCountryFeature).addTo(map);
                    map.fitBounds(countryBordersLayer.getBounds());
                }
            })
            .catch(error => console.error("Error loading country boundaries:", error));
            if (selectedCountryFeature) {
            addMarkerToMap(
                selectedCountryFeature.geometry.coordinates[1],
                selectedCountryFeature.geometry.coordinates[0],
                selectedCountryFeature.properties.name
            );
        }
    }


    // Add an event listener to the select element to change the map location when a country is selected
    const selectCountry = document.getElementById('selectedCountry');
    selectCountry.addEventListener('change', function () {
        const selectedIsoCode = this.value;
        displayCountryBorders(selectedIsoCode);
        

        // Perform reverse geocoding to get the coordinates for the selected country
        fetch(`https://nominatim.openstreetmap.org/search?country=${selectedIsoCode}&format=json`)
            .then(response => response.json())
            .then(data => {
                if (data && data[0] && data[0].lat && data[0].lon) {
                    const latitude = parseFloat(data[0].lat);
                    const longitude = parseFloat(data[0].lon);
                    addMarkerToMap(latitude, longitude, data[0].display_name);
                    // Set the map location to the selected country
                    map.setView([latitude, longitude], 10);
                    getAirportsData(latitude, longitude);
              
                }
            })
            .catch(error => console.error("Error fetching location data:", error));
    });


    const countryInfoButton = L.easyButton('fas fa-info', function () {
        const selectedCountry = $("#selectedCountry").val();
        if (!selectedCountry) {
            alert("Select the country first.");
            return;
        }
        
        if ($("#selectedCountry").val() == "") {
            alert("Select the country first.");
        } else {
            $(document).ajaxStart(function () {
                $(".loading").show();
            });

            $.ajax({
                url: "scripts/restCountries.php",
                type: "POST",
                dataType: "json",
                data: {
                    q: $("#selectedCountry").val(),  
                },
                success: function (result) {
                   
                    console.log(result[0]);
                        // Extract capital, language, and currency information
                    
                        var languages = result.languages;
                        var currencies = result.currencies;
                        // "population" field is already numeric
                        var population = new Intl.NumberFormat().format(result.population);

                        var countryName = result[0].name.common;
                        var countryFullName = result[0].name.official;
                        // Update your modal content with the retrieved information
                        $("#region").html(result[0].region);
                        $("#sub-region").html(result[0].subregion);
                        $("#timezones").html(result[0].timezones[0]);
                        $("#general-info-title").html(countryName + '<br>' + countryFullName);
                        $("#capital-city").html(result[0].capital[0]);
                        $("#population").html(result[0].population.toLocaleString());
                        $("#country-flag").attr("src", result[0].flags.png); // contains the image URL
                        
                        var languagesInfo = "";
                        var languages = result[0].languages;

                        for (var languageCode in languages) {
                            if (languages.hasOwnProperty(languageCode)) {
                                var languageName = languages[languageCode];
                                languagesInfo += languageName + ' - ';
                            }
                        }

                        $("#languages-spoken").html(languagesInfo);

                        var currenciesInfo = "";
                        var currencies = result[0].currencies;

                        for (var currencyCode in currencies) {
                            if (currencies.hasOwnProperty(currencyCode)) {
                                var currency = currencies[currencyCode];
                                currenciesInfo += currency.name + ' - ' + currency.symbol + '<br>';
                            }
                        }

                        var currenciesData = result[0].currencies ;

                  
                        var currencyCode = Object.keys(currenciesData)[0];
                        
                        $("#currencies").html(currenciesInfo);
                        $.ajax({
                            url: "scripts/currencyExchange.php",
                            type: "GET",
                            data: {
                                base_currency: "USD",  
                                target_currency: currencyCode,   
                            },
                            success: function (exchangeRate) {
                               
                                $("#currenciesExchangeRate").html(exchangeRate);
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log("Error fetching currency exchange rate");
                            },
                        });




                    $("#general-info-modal").modal("show");
                
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("data not sent");
                    console.log(errorThrown);
                    console.log(textStatus);
                    console.log(jqXHR);
                },
                complete: function () {
                    console.log('complete'); 
                    $(document).ajaxStop(function () {
                        $(".loading").hide();
                    });
                },
            });
        }
       
        displayModal("countryInfoModal");
    }).addTo(map);


    const weatherButton = L.easyButton('fas fa-cloud', function () {
        const selectedVal = $("#selectedCountry").val();
        var selectedOption = $("#selectedCountry").find(':selected');
        const selectedCountry = selectedOption.data('name');

        if (!selectedCountry) {
            alert("Select the country first.");
            return;
        }

        $(".loading").show();

        $.ajax({
            type: 'POST',
            url: 'scripts/weather-forecast.php',
            data: { selectedCountry: selectedCountry },
            dataType: 'json',
            success: function (data) {
                if (data && data.current && data.forecast) {
                const weatherModal = $("#weatherModal");
                const weatherInfo = $("#weather-info");
                weatherInfo.empty();

                // Display current weather information
                displayCurrentWeather(data.current, weatherInfo);

                // Display forecast information
                displayForecast(data.forecast, weatherInfo);

                // Show the modal
                weatherModal.modal("show");
            } else {
                console.error("Invalid data format in the response");
            }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Error fetching weather data");
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            },
            complete: function () {
                // Hide loading spinner
                $(".loading").hide();
            }
        });

        displayModal("weatherModal");
    }).addTo(map);


    function displayCurrentWeather(currentWeather, weatherInfo) {
        weatherInfo.append(
        "<h3>" + currentWeather.name + "</h3>" +
        "<div style='display: flex; justify-content: space-between; align-items: center;'>" +
        "<div>" +
        "<p style='margin-bottom: 0; font-weight: bold; font-size: 1rem;'>Today</p>" +
        "<p style='text-transform: capitalize; font-size: 1rem; margin-bottom: 0;'>" + currentWeather.weather[0].description + "</p>" +
        "</div>" +
        "<div style='display: flex; align-items: center;'>" +
        "<img src='https://openweathermap.org/img/w/" + currentWeather.weather[0].icon + ".png' style='width: 80px; height: 80px;' />" +
        "<div style='width: 40px; text-align: center;'>" +
        "<p style='margin-bottom: 0; font-weight: bold; font-size: 1rem;'>" + Math.round(currentWeather.main.temp_max) + "&deg;C</p>" +
        "<p style='font-size: 1rem; margin-bottom: 0;'>" + Math.round(currentWeather.main.temp_min) + "&deg;C</p>" + 
        "</div>" +
        "</div>" +
        "</div>" +
        "<hr style='margin: 0;'>"
    );
    }

    function displayForecast(forecastData, weatherInfo) {
    // Clear the existing content in the forecast row
    weatherInfo.find('.ow-row.ow-forecast').empty();

    // Display forecast information for the next two unique days
    let uniqueDays = [];
    let dayCount = 0;
    for (let i = 0; i < forecastData.list.length; i++) {
        let forecastItem = forecastData.list[i];
        let forecastDate = new Date(forecastItem.dt_txt);
        
        // Format the forecast date to "Saturday, 12 December" format
        let forecastDay = forecastDate.toLocaleString('en-US', { weekday: 'long', day: 'numeric', month: 'long' });

        // Check if the day is not already added and limit to two days
        if (!uniqueDays.includes(forecastDay) && dayCount < 2) {
            let forecastDescription = forecastItem.weather[0].description;
            let forecastIcon = forecastItem.weather[0].icon;
            let forecastTempMax = Math.round(forecastItem.main.temp_max);
            let forecastTempMin = Math.round(forecastItem.main.temp_min);

            weatherInfo.append(
                "<div style='display: flex; justify-content: space-between; align-items: center;'>" +
                "<div>" +
                "<p style='margin-bottom: 0; font-weight: bold; font-size: 1rem;'>" + forecastDay + "</p>" +
                "<p style='text-transform: capitalize; font-size: 1rem; margin-bottom: 0;'>" + forecastDescription + "</p>" +
                "</div>" +
                "<div style='display: flex; align-items: center;'>" +
                "<img src='https://openweathermap.org/img/w/" + forecastIcon + ".png' style='width: 80px; height: 80px;' />" +
                "<div style='width: 40px; text-align: center;'>" +
                "<p style='margin-bottom: 0; font-weight: bold; font-size: 1rem;'>" + forecastTempMax + "&deg;C</p>" +
                "<p style='font-size: 1rem; margin-bottom: 0;'>" + forecastTempMin + "&deg;C</p>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "<hr style='margin: 0;'>"  
                );

            uniqueDays.push(forecastDay);
            dayCount++;
        }
    }
}


    const wikiButton = L.easyButton('fas fa-book', function () {
        const selectedCountry = $("#selectedCountry").val();
        if (!selectedCountry) {
            alert("Select the country first.");
            return;
        }
 
        if ($("#selectedCountry").val() == "") {
            alert("Select the country first.");
        } else {
            // Show loading spinner
            $(".loading").show();
 
            $.ajax({
                url: "scripts/wiki.php",  
                type: "POST",
                dataType: "xml",
                data: {
                    selectedCountry: $("#selectedCountry").val(),
                },
                success: function (result) {
                  
                    const wikiModal = $("#wikiModal");
                    const wikiInfo = $("#wiki-info");
                    console.log(result);
                   
                      wikiInfo.empty();

                  
                    $(result).find('entry').each(function() {
                        const title = $(this).find('title').text();
                        const summary = $(this).find('summary').text();
                        const feature = $(this).find('feature').text();
                        const countryCode = $(this).find('countryCode').text();
                        const elevation = $(this).find('elevation').text();
                        const lat = $(this).find('lat').text();
                        const lng = $(this).find('lng').text();
                        const wikipediaUrl = $(this).find('wikipediaUrl').text();
                        const thumbnailImg = $(this).find('thumbnailImg').text();
                        
                        // Create HTML elements to display the information
                        const entryDiv = $("<div>");
                        entryDiv.append("<h2>" + title + "</h2>");
                        entryDiv.append("<p><strong>Summary:</strong> " + summary + "</p>");
                        entryDiv.append("<p><strong>Feature:</strong> " + feature + "</p>");
                        entryDiv.append("<p><strong>Country Code:</strong> " + countryCode + "</p>");
                        entryDiv.append("<p><strong>Elevation:</strong> " + elevation + "</p>"); 
                        entryDiv.append("<img src='" + thumbnailImg + "' alt='Thumbnail Image'>");
                        entryDiv.append("<p><a href='" + wikipediaUrl + "' target='_blank' class='btn btn-dark'>Read More</a></p>");

                        // Append the entry to the modal content
                        wikiInfo.append(entryDiv);
                    });


                    // Show the modal
                    wikiModal.modal("show");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("Error fetching wikipedia data");
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                complete: function () {
                    // Hide loading spinner
                    $(".loading").hide();
                },
            });
        }

        displayModal("wikiModal");
    }).addTo(map);


    const newsButton = L.easyButton('fas fa-newspaper', function () {
        const selectedCountry = $("#selectedCountry").val();
        if (!selectedCountry) {
            alert("Select the country first.");
            return;
        }

        if ($("#selectedCountry").val() == "") {
            alert("Select the country first.");
        } else {
            // Show loading spinner
            $(".loading").show();
 
            $.ajax({
                url: "scripts/news.php",  
                type: "POST",
                dataType: "json",
                data: {
                    selectedCountry: $("#selectedCountry").val(),
                },
                success: function (response) {
                        // Clear existing content
                        $("#news-info").empty();

                        // Iterate through the result and append details to the modal
                        response.results.forEach(function (article) {
                            $("#news-info").append(
                                '<div class="news-item">' +
                                '<h5>' + article.title + '</h5>' +
                                '<small><b>Author:</b> ' + article.creator + '</small> | ' +
                                '<small><b>Published at</b> ' + article.pubDate + '</small>' +
                                '<p class="mt-2">' + article.description + '</p>' +
                                '<p><a href="' + article.link + '" class="btn btn-sm btn-dark" target="_blank">Read More</a></p>' +
                                '</div><hr>'
                            );
                        });

                        // Show the modal
                        $("#newsModal").modal("show");
                     
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("Error fetching news data");
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                complete: function () {
                    // Hide loading spinner
                    $(".loading").hide();
                },
            });
        }

        displayModal("newsModal");
    }).addTo(map);


    // Function to display modal with content
    function displayModal(modalId) {
        // $(`#${modalId} .modal-body #${modalId}-info`).html(content);
        $(`#${modalId}`).modal('show');
        $(`#${modalId}`).addClass('show');
    }

    const currencyInfoButton = L.easyButton('fas fa-sync-alt', function () {
    const selectedCountry = $("#selectedCountry").val();
    if (!selectedCountry) {
        alert("Select the country first.");
        return;
    }

    if ($("#selectedCountry").val() == "") {
        alert("Select the country first.");
    } else {
        $(document).ajaxStart(function () {
            $(".loading").show();
        });

        $.ajax({
            url: "scripts/restCountries.php",
            type: "POST",
            dataType: "json",
            data: {
                q: $("#selectedCountry").val(),
            },
            success: function (result) {
                console.log(result[0]);
                var currencies = result[0].currencies;
                var currenciesInfo = "";

                for (var currencyCode in currencies) {
                    if (currencies.hasOwnProperty(currencyCode)) {
                        var currency = currencies[currencyCode];
                        currenciesInfo += currency.name + ' - ' + currency.symbol + '<br>';
                    }
                }

                var currenciesData = result[0].currencies;
                var currencyCode = Object.keys(currenciesData)[0];

                $("#currency").html(currenciesInfo);

                // Set default value for the input field
                $("#currencyValue").val("1");

                // Fetch exchange rate for default value
                $.ajax({
                    url: "scripts/currencyCalculator.php",
                    type: "GET",
                    data: {
                        base_currency: "USD",
                        target_currency: currencyCode,
                        amount: "1", // Default value is 1
                    },
                    success: function (exchangeRate) {
                        $("#currencyExchangeRate").html(exchangeRate);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log("Error fetching currency exchange rate");
                    },
                });

                // Bind input event to currencyValue field for live updates
                const debouncedFetchExchangeRate = _.debounce(function () {
                    const enteredValue = $("#currencyValue").val();

                    $.ajax({
                        url: "scripts/currencyCalculator.php",
                        type: "GET",
                        data: {
                            base_currency: "USD",
                            target_currency: currencyCode,
                            amount: enteredValue,
                        },
                        success: function (exchangeRate) {
                            $("#currencyExchangeRate").html(exchangeRate);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("Error fetching currency exchange rate");
                        },
                    });
                }, 1000); // Set the delay to 1000 milliseconds (1 second)

                // Bind the debounced function to the input event
                $("#currencyValue").on("input", function () {
                    debouncedFetchExchangeRate();
                });

                $("#general-info-modal").modal("show");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("data not sent");
                console.log(errorThrown);
                console.log(textStatus);
                console.log(jqXHR);
            },
            complete: function () {
                console.log('complete');
                $(document).ajaxStop(function () {
                    $(".loading").hide();
                });
            },
        });
    }

    displayModal("currencyInfoModal");
}).addTo(map);

});

      
    </script>

</body>
</html>