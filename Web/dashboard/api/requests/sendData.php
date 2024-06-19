<?php
    require_once 'apiConfig.php';
    
    function sendDataToApi($route, $data)
    {
        global $apiBaseUrl;
    
        // Ensure $apiBaseUrl is defined
        if (!isset($apiBaseUrl)) {
            die('API base URL is not defined.');
        }
    
        $apiUrl = $apiBaseUrl . $route;
    
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
        $apiResponse = curl_exec($ch);
    
        if (curl_errno($ch)) {
            die('Curl error: ' . curl_error($ch));
        }
    
        curl_close($ch);
    
        return $apiResponse;
    }
?>