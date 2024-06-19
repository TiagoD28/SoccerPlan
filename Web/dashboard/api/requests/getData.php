<?php

require_once '../../api/requests/apiConfig.php';
    // require_once '../api/requests/apiConfig.php';

    function getDataFromApi($route){
        global $apiBaseUrl;

        $apiUrl = $apiBaseUrl . $route;

        // Initialize cURL session
        $ch = curl_init($apiUrl);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // Execute cURL session and get the response
        $apiResponse = curl_exec($ch);

        // Close cURL session
        curl_close($ch);

        // Decode JSON response
        $responseData = json_decode($apiResponse, true);

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