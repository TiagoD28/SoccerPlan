<?php
    // require_once '../../api/requests/apiConfig.php';
    // require_once '../api/requests/apiConfig.php';
    require_once 'apiConfig.php';

    function sendDataToApi($route, $data){
        global $apiBaseUrl;

        // Ensure $apiBaseUrl is defined
        if (!isset($apiBaseUrl)) {
            die('API base URL is not defined.');
        }

        // Ensure $data is not overridden by the function parameter
        if (empty($data)) {
            // If $data is empty, use the data from the request payload
            $json_data = file_get_contents("php://input");
            $data = json_decode($json_data, true);
        }

        // Check if $data is valid JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            die('Invalid JSON data.');
        }

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($data),
            ],
        ];

        $apiUrl = $apiBaseUrl . $route;

        $context = stream_context_create($options);
        $apiResponse = file_get_contents($apiUrl, false, $context);

        return $apiResponse;

        // if (isset($apiResponse['status']) && $apiResponse['status'] === '200') {
        //     // Access the 'data' key and iterate through the clubs
            // return $result;
        // } else {
        //     // Handle the case where the API response indicates an error
        //     echo "Error: {$apiResponse['message']}";
        // }
    }
?>