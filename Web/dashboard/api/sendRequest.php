<?php 

    require_once './requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $idClub = $_GET['idClub'];
    $idUser = $_GET['idUser'];

    // echo $idClub;
    // echo $idUser;

    $data = [
        "idClub" => $idClub,
        "idUser" => $idUser
    ];

    $route = 'Requests/index.php?route=sendRequestClub';
    $apiResponse = sendDataToApi($route, $data);
    $decodedApiResponse = json_decode($apiResponse, true);
    
    if($decodedApiResponse['status'] == '400'){
        echo $decodedApiResponse['message'];
    } else {
        // echo $decodedApiResponse['message'];
        header("Location: ../views/adminClub/base.php");
    }
    // Decode the JSON response
    // $decodedResponse = json_decode($apiResponse, true);

?>