<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

if($route == 'getAdversaries'){
    try{
        $sql = "SELECT * FROM AdversaryTeams";
        $stm = $pdo->prepare($sql);
        // $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->execute();
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        sendSuccessResponse('200', 'Success', $data);
    } catch(PDOException $e){
        sendErrorResponse('400', 'Error: ' . $e->getMessage());
    }


} else if($route == 'createAdversary'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idChampionship = isset($requestData['idChampionship']) ? $requestData['idChampionship'] : NULL;
    $clubName = isset($requestData['clubName']) ? $requestData['clubName'] : NULL;
    $age = isset($requestData['age']) ? $requestData['age'] : NULL;
    $scored = NULL;
    $conceded = NULL;

    if($clubName == NULL){
        sendErrorResponse('400', 'Must introduce club name!');
    }

    try{
        $sql = "INSERT INTO AdversaryTeams (nameClub, age, goalsScored, goalsConceded, idChampionship)
                VALUES (:nameClub, :age, :goalsScored, :goalsConceded, :idChampionship)";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':nameClub', $clubName, PDO::PARAM_STR);
        $stm->bindParam(':age', $age, PDO::PARAM_STR);
        $stm->bindParam(':goalsScored', $scored, PDO::PARAM_INT);
        $stm->bindParam(':goalsConceded', $conceded, PDO::PARAM_INT);
        $stm->bindParam(':idChampionship', $idChampionship, PDO::PARAM_INT);
        $stm->execute();

        sendSuccessResponse('200', 'Adversary Team created successfuly!');
    
    } catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }
} else if($route == 'getAdversariesChamp'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idChampionship = isset($requestData['idChampionship']) ? $requestData['idChampionship'] : NULL;

    try{
        $sql = "SELECT * FROM AdversaryTeams WHERE idChampionship = :idChampionship";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idChampionship', $idChampionship, PDO::PARAM_INT);
        $stm->execute();
        $adversaries = $stm->fetchAll(PDO::FETCH_ASSOC);

        sendSuccessResponse('200', 'Success', $adversaries);
    }catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }


}