<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $data = []; // Initialize $data outside the conditional block
    if(isset($_POST['addAdversaryForm'])){
        // Get the values from the form
        $idChampionship = $_POST['idChampionship'];
        $clubName = $_POST['clubName'];
        $age = $_POST['agesDropdown'];
    }

    $data = [
        'idChampionship' => $idChampionship, 
        'clubName' => $clubName,
        'age' => $age
    ];

    // Now $data is defined in all code paths
    $route = 'Adversaries/index.php?route=createAdversary';   

    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);
    $_SESSION['toast'] = $decodedResponse['status'];
    $_SESSION['toastMessage'] = $decodedResponse['message'];

    header('Location: ../../views/adminClub/base.php?route=championships&adversariesModal=true&championshipId='. $idChampionship);
?>