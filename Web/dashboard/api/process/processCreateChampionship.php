<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $data = []; // Initialize $data outside the conditional block
    if(isset($_POST['createChampionship'])){
        // Get the values from the form
        $nameChampionship = $_POST['nameChampionship'];
        $season = $_POST['seasonsDropdown'];
        $rank = $_POST['ranksDropdown'];
        $fieldOf = $_POST['fieldsDropdown'];
        $idClub = $_SESSION['idClub'];
        $idTeam = $_POST['teamsDropdown'];
    }

    $data = [
        'nameChampionship' => $nameChampionship, 
        'season' => $season,
        'rank' => $rank,
        'fieldOf' => $fieldOf,
        'idClub' => $idClub,
        'idTeam' => $idTeam
    ];

    // Now $data is defined in all code paths
    $route = 'Championships/index.php?route=createChampionshipClub';   

    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);
    $_SESSION['toast'] = $decodedResponse['status'];
    $_SESSION['toastMessage'] = $decodedResponse['message'];

    header('Location: ../../views/adminClub/base.php?route=championships');
?>