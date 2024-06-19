<?php
    require_once '../../api/requests/apiConfig.php';
    // require_once '../api/requests/apiConfig.php';

    function getDataFromApi($route){
        global $apiBaseUrl;

        $apiUrl = $apiBaseUrl . $route;

        // Make a GET request to the API
        $apiResponse = file_get_contents($apiUrl);

        // Decode JSON response
        $responseData = json_decode($apiResponse, true);
        // echo $apiUrl.'<br>';

        // Check if the decoding was successful
        if ($responseData !== null) {
            // Check if the 'status' key is set to success
            if (isset($responseData['status']) && $responseData['status'] === '200') {
                // Access the 'data' key and iterate through the clubs
                return $responseData;
            } else {
                // Handle the case where the API response indicates an error
                echo "Error: {$responseData['message']}";
            }
        } else {
            // Handle the case where JSON decoding failed
            echo "Error decoding API response.";
        }
    }
?>