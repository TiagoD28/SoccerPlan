<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $data = []; // Initialize $data outside the conditional block

    if(isset($_POST['generateCode'])){

        $idGenerator = $_SESSION['idUser'];
        $idReceiver = $_POST['user'];
        $idClub = $_SESSION['idClub'];
        $clubRandomCode = $_POST['clubRandomCode'];
    
        $data = [
            'idClub' => $idClub, 
            'idGenerator' => $idGenerator,
            'idReceiver' => $idReceiver,
            'clubCode' => $clubRandomCode,
        ];
    }

    $route = 'Codes/index.php?route=generateCodeClub';  

    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);
    $_SESSION['toast'] = $decodedResponse['status'];
    $_SESSION['toastMessage'] = $decodedResponse['message'];

    header('Location: ../../views/adminClub/base.php?route=club');
?>