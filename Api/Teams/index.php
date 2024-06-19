<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

function teamExists($name, $pdo){
    $sql = "SELECT nome FROM Teams WHERE nome = :nome";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':nome', $name, PDO::PARAM_STR);
    $stm->execute();

    $count = $stm->fetchColumn();

    return $count > 0;
}

if($route == 'createTeam'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }
    
    $name = isset($requestData['name']) ? $requestData['name'] : '';
    $age = isset($requestData['age']) ? $requestData['age'] : '';
    // $league = isset($requestData['league']) ? $requestData['league'] : '';
    $img = isset($requestData['img']) ? $requestData['img'] : '';
    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';

    $errors = false;
    if($name == ''){
        $errors = true;
        sendErrorResponse('410', 'Name must be introduced!'); 
    }
    if($age == ''){
        $errors = true;
        sendErrorResponse('430', 'Age must be introduced!');
    }
    // instead of img i can put the age of team inside circle
    // if($img == ''){
    //     $errors = true;
    //     sendErrorResponse('440', 'Local must be introduced!');
    // }
    // Team could not have an coach yet
    // if($idCoach == ''){
    //     $errors = true;
    //     sendErrorResponse('440', 'Nacionality must be introduced!');
    // }
    if(teamExists($name, $pdo)){
        $errors = true;
        sendErrorResponse('450', 'Team already exist!');
    }
    
    $sql = "INSERT INTO Teams (nome, age, league, img, idCoach)
    VALUES (:nome, :age, :league, :img, :idCoach)";
    $stm = $pdo ->prepare($sql);
    $stm->bindParam(':nome', $name, PDO::PARAM_STR);
    $stm->bindParam(':age', $age, PDO::PARAM_INT);
    $stm->bindParam(':league', $league, PDO::PARAM_STR);
    $stm->bindParam(':img', $img, PDO::PARAM_STR);
    $stm->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
    $stm->execute();

    sendSuccessResponse('200', 'Successful registration!', '');

} else if($route == 'getTeams'){
    $sql = "SELECT * FROM Teams";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    sendSuccessResponse('200', 'Success', $data);
    // header('Content-Type: application/json');
    // echo json_encode([
    //     'status' => '200',
    //     'message' => 'sucesso',
    //     'data' => $data,
    // ]);
} else if($route == 'getAvatars'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
        exit;
    }

    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';

    $errors = false;
    if ($idTeam == '') {
        $errors = true;
        sendErrorResponse('410', 'Must have team introduced!');
        exit; // Exit the script if there are errors
    }

    $sql = "SELECT P.idUser, P.img, U.username
            FROM Players P
            INNER JOIN Users U ON P.idUser = U.idUser
            WHERE P.idTeam = :idTeam";

    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idTeam', $idTeam);
    $stm->execute();
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    if (empty($data)) {
        sendErrorResponse('404', 'No players found for the specified team');
    } else {
        sendSuccessResponse('200', 'Success', $data);
    }

    // $requestData = json_decode(file_get_contents("php://input"), true);
    // if ($requestData === null) {
    //     // Handle JSON decoding error
    //     sendErrorResponse('400', 'Invalide JSON data');
    //     exit;
    // }
    
    // $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';

    // $errors = false;
    // if($idTeam == ''){
    //     $errors = true;
    //     sendErrorResponse('410', 'Must have team introduced!'); 
    // }

    // $sql = "SELECT idUser, img FROM Players WHERE idTeam = :idTeam";
    // $stm = $pdo->prepare($sql);
    // $stm->bindParam(':idTeam', $idTeam);
    // $stm->execute();
    // $data = $stm->fetch(PDO::FETCH_ASSOC);

    // if($data != 0){
    //     $sql1 = "SELECT username FROM Users WHERE idUser = :idUser";
    //     $stm1 = $pdo->prepare($sql1);
    //     $stm1->bindParam(':idUser', $data['idUser']);
    //     $stm1->execute();
    //     $data1 = $stm1->fetchAll(PDO::FETCH_ASSOC);

    //     if($data != 0){
    //         $finalData = array_merge($data, $data1);
    //     }
    // }

    // sendSuccessResponse('200', 'Success', $finalData);
}