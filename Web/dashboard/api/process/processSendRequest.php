<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $data = []; // Initialize $data outside the conditional block

    if(isset($_GET['idClub'])){

        // Get the values from the URL using $_GET
        $idRequester = $_GET['idRequester'];
        $idClub = $_GET['idClub'];

        $data = [
            'idRequester' => $idRequester,
            'idClub' => $idClub,
        ];
    }   


    // Now $data is defined in all code paths
    $route = 'Requests/index.php?route=sendRequestClub'; 

    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);
    $_SESSION['toast'] = $decodedResponse['status'];
    $_SESSION['toastMessage'] = $decodedResponse['message'];

    header('Location: ../../views/adminClub/base.php?route=club');
?>