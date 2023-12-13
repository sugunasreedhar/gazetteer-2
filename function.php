<?php

$json_data = file_get_contents('countryBorders.geo.json');
$data = json_decode($json_data, true);

function getCountryList() {
    global $data; // Make the $data variable accessible inside the function

    // Fetch country data from your parsed JSON
    $countryList = [];
    foreach ($data['features'] as $feature) {
        $countryList[] = [
            'iso_a2' => $feature['properties']['iso_a2'],
            'name' => $feature['properties']['name'],
        ];
    }
    return $countryList;
}

// Get the country data from the function
$countries = getCountryList();

 

