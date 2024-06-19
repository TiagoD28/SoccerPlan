<?php
// Load your database connection and data.php
require_once('../Connection/data.php');
require_once('../Response/index.php');

// $route = $_GET['route'];
$route = isset($_GET['route']) ? $_GET['route'] : null;

// Define routes and their corresponding actions
if($route == 'getUsers'){
    $sql = "SELECT email, pass, username FROM Users";
    $stm = $pdo->prepare($sql);
    $result = $stm->execute();
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);
    sendSuccessResponse('200', 'Sucess', $data);


} else if($route == 'getInfoUpdatedUser'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    $tablename = '';
    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    // $typeUser = isset($requestData['typeUser']) ? $requestData['typeUser'] : '';

    $errors = false;
    if($idUser == ''){
        $errors = true;
        sendErrorResponse('400-3', 'Id User must be defined!');
    }

    $sql = "SELECT * FROM Users WHERE idUser = :idUser";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stm->execute();
    $data = $stm->fetch(PDO::FETCH_ASSOC);

    if($data == 0){
        sendErrorResponse('400', 'Error getting the info of the User!');
    }

    // echo $data['typeUser'];
    if($data['typeUser'] == 'Coach'){
        $tablename = 'Coaches';
    } else if($data['typeUser'] == 'Player') {
        $tablename = 'Players';
    } 

    $sql1 = "SELECT * FROM $tablename WHERE idUser = :idUser";
    $stm1 = $pdo->prepare($sql1);
    $stm1->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stm1->execute();
    $data1 = $stm1->fetch(PDO::FETCH_ASSOC);

    if($data1 == 0){
        sendErrorResponse('400', 'Error getting the info of table ' + $tablename);
    }

    $finaldata = array_merge($data, $data1);


    if($data['typeUser'] == 'Coach'){
        $sql2 = "SELECT idTeam FROM Teams WHERE idCoach = :idCoach";
        $stm2 = $pdo->prepare($sql2);
        $stm2->bindParam(':idCoach', $data1['idCoach'], PDO::PARAM_INT);
        $stm2->execute();
        $data2 = $stm2->fetch(PDO::FETCH_ASSOC);

        if($data2 != 0){
            $finaldata['idTeam'] = $data2['idTeam'];
        } else {
            $finaldata['idTeam'] = '';
        }
    }

    sendSuccessResponse('200', 'Sucess', $finaldata);
} else {
    // Handle 404 Not Found
    http_response_code(404);
    echo 'Route not found!';  
}