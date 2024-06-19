<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../api/requests/sendData.php';

// Definição do cabeçalho
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

    $email = $_POST['email'];
    $password = $_POST['password'];

    $data = [
        'email' => $email,
        'password' => $password
    ];

    $route = 'Authentication/index.php?route=loginWeb';
    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);

    if(isset($decodedResponse['status']) && $decodedResponse['status'] == '200'){
        $responseData = $decodedResponse['data'];
        // echo $responseData['idUser'];
        $_SESSION['toast'] = $decodedResponse['status'];
        $_SESSION['toastMessage'] = $decodedResponse['message'];
        $_SESSION['status'] = $decodedResponse['status'];
        $_SESSION['message'] = $decodedResponse['message'];
        $_SESSION['idUser'] = $responseData['idUser'];
        
        if($responseData['typeUser'] == 'ClubAdmin'){
            $_SESSION['idClubAdmin'] = $responseData['idClubAdmin'];
            $_SESSION['idClub'] = $responseData['idClub'];
        } else if($responseData['typeUser'] == 'Employer'){
            $_SESSION['idEmployer'] = $responseData['idEmployer'];
            $_SESSION['idClub'] = $responseData['idClub'];
        }
        $_SESSION['email'] = $responseData['email'];
        $_SESSION['typeUser'] = $responseData['typeUser'];
        $_SESSION['firstName'] = $responseData['firstName'];
        $_SESSION['lastName'] = $responseData['lastName'];
        $_SESSION['age'] = $responseData['age'];
        $_SESSION['nacionality'] = $responseData['nacionality'];
        $_SESSION['phoneNumber'] = $responseData['phoneNumber'];
        $_SESSION['img'] = $responseData['img'];
        header("Location: ../../index.php");
        exit;
    } 
    else {
        $_SESSION['toast'] = $decodedResponse['status'];
        $_SESSION['toastMessage'] = $decodedResponse['message'];
        $_SESSION['status'] = $decodedResponse['status'];
        $_SESSION['message'] = $decodedResponse['message'];
        header("Location: ../../views/authentication/login.php");
        exit;
    }