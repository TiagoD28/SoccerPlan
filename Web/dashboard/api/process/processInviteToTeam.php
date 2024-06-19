<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $data = []; // Initialize $data outside the conditional block

    if(isset($_POST['generateCode'])){

        // die($_POST['user']);

        $idGenerator = $_SESSION['idUser'];
        $idReceiver = $_POST['user'];
        $idTeam = $_POST['idTeam'];
        $idClub = $_SESSION['idClub'];
        $clubRandomCode = $_POST['clubRandomCode'];
        $teamRandomCode = $_POST['teamRandomCode'];
    
        // If no image is uploaded, set other data
        $data = [
            'idClub' => $idClub, 
            'idTeam' => $idTeam,
            'idGenerator' => $idGenerator,
            'idReceiver' => $idReceiver,
            'clubCode' => $clubRandomCode,
            'teamCode' => $teamRandomCode
        ];
    }

    // Now $data is defined in all code paths
    $route = 'Codes/index.php?route=generateCode';  

    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);
    $_SESSION['toast'] = $decodedResponse['status'];
    $_SESSION['toastMessage'] = $decodedResponse['message'];

    header('Location: ../../views/adminClub/base.php?route=teams');
?>