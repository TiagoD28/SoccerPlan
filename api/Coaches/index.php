<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

function findUserById($users, $userId) {
    foreach ($users as $user) {
        if ($user['idUser'] == $userId) {
            return $user;
        }
    }
    return array(); // Return an empty array if user is not found
}

if($route == 'getCoach'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';

    // data of table Coaches
    $sql = "SELECT * FROM Coaches WHERE idCoach = :idCoach";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
    $stm->execute();

    $dataCoach = $stm->fetchAll(PDO::FETCH_ASSOC);

    //data of table Teams
    $sql = "SELECT idTeam FROM Teams WHERE idCoach = :idCoach";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
    $stm->execute();

    $dataTeam = $stm->fetchAll(PDO::FETCH_ASSOC);

    $allData = array_merge($dataCoach, $dataTeam);

    sendSuccessResponse('200', 'Success', $allData);


} else if($route === 'getCoaches'){
    $sqlCoaches = "SELECT * FROM Coaches";
    $stmCoaches = $pdo->prepare($sqlCoaches);
    $stmCoaches->execute();

    $dataCoaches = $stmCoaches->fetchAll(PDO::FETCH_ASSOC);

    if($dataCoaches == false){
        sendErrorResponse('400', 'There is no Coaches!');
    }

    // Extracting idUser values from the fetched data
    $idUserValues = array_column($dataCoaches, 'idUser');

    // Fetching additional data from the "users" table based on idUser values
    $sqlUsers = "SELECT * FROM Users WHERE idUser IN (" . implode(',', $idUserValues) . ")";
    $stmUsers = $pdo->prepare($sqlUsers);
    $stmUsers->execute();

    $dataUsers = $stmUsers->fetchAll(PDO::FETCH_ASSOC);

    $mergedData = array();
    foreach ($dataCoaches as $coach) {
        $mergedData[] = $coach + findUserById($dataUsers, $coach['idUser']);
    }
    
    sendSuccessResponse('200', 'Success getting all coaches!', $mergedData);


} else if($route == 'getCoachesClub'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';

    // data of table Coaches
    $sql = "SELECT * FROM Coaches WHERE idClub = :idClub";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    $stm->execute();

    $dataCoach = $stm->fetchAll(PDO::FETCH_ASSOC);

    foreach ($dataCoach as $coach) {
        //data of table Teams
        $sql = "SELECT idTeam FROM Teams WHERE idCoach = :idCoach";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idCoach', $coach['idCoach'], PDO::PARAM_INT);
        $stm->execute();

        $dataTeam = $stm->fetch(PDO::FETCH_ASSOC);        

            $sqlUser = "SELECT * FROM Users WHERE idUser = :idUser";
            $stmUser = $pdo->prepare($sqlUser);
            $stmUser->bindParam(':idUser', $coach['idUser']);
            $stmUser->execute();

            $dataUser = $stmUser->fetch(PDO::FETCH_ASSOC);

            $allData = array_merge($dataUser, $coach);

            $allCoaches[] = $allData;
    }

    sendSuccessResponse('200', 'Success', $allCoaches);

} else if($route == 'leaveClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    if($idCoach == ''){
        sendErrorResponse('400', 'Id coach empty');
    }

    try{
        $sql = "UPDATE Coaches SET idClub = NULL WHERE idCoach = :idCoach";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
        $stm->execute();

        $sql1 = "UPDATE Teams SET idCoach = NULL WHERE idCoach = :idCoach";
        $stm1 = $pdo->prepare($sql1);
        $stm1->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
        $stm1->execute();

        sendSuccessResponse('200', 'Coach left the club successfuly!');

    }catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

}