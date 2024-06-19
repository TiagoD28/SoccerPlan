<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $data = []; // Initialize $data outside the conditional block

    if(isset($_POST['deleteTeam'])){

        $idTeam = $_POST['idTeam'];
    
        // If no image is uploaded, set other data
        $data = [ 
            'idTeam' => $idTeam,
        ];
    }

    // Now $data is defined in all code paths
    $route = 'Teams/index.php?route=deleteTeam';  

    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);
    $_SESSION['toast'] = $decodedResponse['status'];
    $_SESSION['toastMessage'] = $decodedResponse['message'];

    header('Location: ../../views/adminClub/base.php?route=teams');
?>