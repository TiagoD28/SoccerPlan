<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Carregar configurações
// require_once './config.php';
// $pdo = connectDB($db);

require_once '../../api/requests/sendData.php';

// Carregar classe
// require_once '../../objects/User.php';
// $user = new User($pdo);

// Carregar JWT
// require './vendor/autoload.php';

use \Firebase\JWT\JWT;

// Definição do cabeçalho
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Obter dados do POST
// $data = json_decode(file_get_contents("php://input"));

// $response = array();
    $email = $_POST['email'];
    // $user = $_POST['user'];
    $password = $_POST['password'];

    $data = [
        // 'user' => $user,
        'email' => $email,
        'password' => $password
    ];
    // echo $email;
    // echo $password;

    // Query
    // $sql = "SELECT * FROM Users WHERE email = :email";
    // $stm = $pdo->prepare($sql);
    // // $stm->bindParam(':email', $email, PDO::PARAM_STR);
    // $stm->bindValue(':email', (string)$email);
    // $result = $stm->execute();
    // echo 'Ola';
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
        // echo $responseData['typeUser'];
        if($responseData['typeUser'] == 'Admin'){
            $_SESSION['idAdmin'] = $responseData['idAdmin'];
        } else if($responseData['typeUser'] == 'ClubAdmin'){
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
        header("Location: ../../index.php");
        exit;
    } 
    else {
        $_SESSION['toast'] = $decodedResponse['status'];
        $_SESSION['toastMessage'] = $decodedResponse['message'];
        $_SESSION['status'] = $decodedResponse['status'];
        $_SESSION['message'] = $decodedResponse['message'];
        header("Location: ../../views/authentication/login.php");
    }

    // if($result) {
    //     if($stm->rowCount() === 0) {
    //         http_response_code(404);
    //         $response['mesage'] = "No user!";
    //         echo json_encode($response);
    //         exit;
    //     } else {
    //         require_once('vendor/autoload.php');
    //         require_once('config.php');
    //         $row = $stm->fetch();
    //         echo $row[0];
    //         if(password_verify($password, $row['pass'])){

    //             // get idClubAdmin or idEmployer
    //             $isUser = false;

    //             $sql1 = "SELECT * FROM Admins WHERE idUser = :idUser";
    //             $stm1 = $pdo->prepare($sql1);
    //             // $stm->bindParam(':email', $email, PDO::PARAM_STR);
    //             $stm1->bindValue(':idUser', $row['idUser']);
    //             $result1 = $stm1->execute();
    //             $row1 = $stm1->fetch();
    //             // end

    //             $sql2 = "SELECT * FROM Employers WHERE idUser = :idUser";
    //             $stm2 = $pdo->prepare($sql2);
    //             // $stm->bindParam(':email', $email, PDO::PARAM_STR);
    //             $stm2->bindValue(':idUser', $row['idUser']);
    //             $result2 = $stm2->execute();
    //             $row2 = $stm2->fetch();

    //             if($row1)
                
    //             // Criar token
    //             $token = array(
    //                 "iss" => $jwt_conf['iss'],
    //                 "jti" => $jwt_conf['jti'],
    //                 "iat" => $jwt_conf['iat'],
    //                 "nbf" => $jwt_conf['nbf'],
    //                 "exp" => $jwt_conf['exp'],
    //                 "data" => array(
    //                     // get data
    //                     "idUser" => $row['idUser'],
    //                     "idAdmin" => $row1['idAdmin'],
    //                     "email" => $row['email'],
    //                     "username" => $row['pass'],
    //                     "firstName" => $row['firstName'],
    //                     "lastName" => $row['lastName'],
    //                     "typeUser" => $row['typeUser']
    //                 )
    //             );

    //             $_SESSION['idUser'] = $row['idUser'];
    //             $_SESSION['idAdmin'] = $row1['idAdmin'];
    //             $_SESSION['email'] = $row['email'];
    //             $_SESSION['typeUser'] = $row['typeUser'];
    //             $_SESSION['firstName'] = $row['firstName'];
    //             $_SESSION['lastName'] = $row['lastName'];
    //             $jwt = \Firebase\JWT\JWT::encode($token, $jwt_conf['key'], 'HS256');
    //             $response['message'] = "Successful login!";
    //             $response['jwt'] = $jwt;
    //             echo json_encode($response);
    //             header("Location: ../index.php");
    //             exit;
    //         } else {
    //             http_response_code(401);
    //             $response['message'] = "Password incorrect!";
    //             echo json_encode($response);
    //         }
            
    //     }
    // } else {
    //     // User not found - 404 Not Found
    //     http_response_code(500);
    //     $response['message'] = "Error querying database";
    //     echo json_encode($response);
    //     // echo json_encode(array("message" => "User not found"));
    // }

