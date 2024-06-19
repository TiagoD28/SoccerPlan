<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $data = []; // Initialize $data outside the conditional block

    if(isset($_GET['code'])){

        // Get the values from the URL using $_GET
        $idUser = $_GET['idUser'];
        $idClub = $_GET['idClub'];
        $idEmployer = $_GET['idEmployer'];
        $code = $_GET['code'];

        $data = [
            'idUser' => $idUser,
            'idClub' => $idClub,
            'idEmployer' => $idEmployer,
            'code' => $code,
        ];
    } else {
        $apiResponse = sendDataToApi($route, $data);
        $decodedResponse = json_decode($apiResponse, true);
        $_SESSION['toast'] = '400';
        $_SESSION['toastMessage'] = 'You must insert the code!';

        header('Location: ../../views/adminClub/base.php?route=club');
        exit;
    }

    // Now $data is defined in all code paths
    $route = 'Codes/index.php?route=sendCodeClub'; 

    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);
    $_SESSION['toast'] = $decodedResponse['status'];
    $_SESSION['toastMessage'] = $decodedResponse['message'];

    if($decodedResponse['status'] == '200'){
        $_SESSION['idClub'] = $_GET['idClub'];
        // die($idClub . ' : ' . $_GET['idClub']);
    }
    header('Location: ../../views/adminClub/base.php?route=club');
    exit;
?>