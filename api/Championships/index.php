<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

if($route == 'getChampionshipsClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';

    // data of table Coaches
    $sql = "SELECT * FROM Championships WHERE idClub = :idClub";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    $stm->execute();

    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    if($data === false){
        sendErrorResponse('400', 'Doesn t exist Championships!');
    }

    sendSuccessResponse('200', 'Success', $data);


} else if($route == 'createChampionshipClub'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }  
    
    $name = isset($requestData['nameChampionship']) ? $requestData['nameChampionship'] : NULL;
    $season = isset($requestData['season']) ? $requestData['season'] : NULL;
    $rank = isset($requestData['rank']) ? $requestData['rank'] : NULL;
    $fieldOf = isset($requestData['fieldOf']) ? $requestData['fieldOf'] : NULL;
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : NULL;
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : NULL;


    if($name == NULL){
        sendErrorResponse('400', 'Must introduce name of the championship!');
    }  


    try {
        // Check if team has championship
        if($idTeam != NULL){
            $sqlCheck = "SELECT * FROM Teams WHERE idTeam = :idTeam AND idChampionship IS NOT NULL";
            $stmCheck = $pdo->prepare($sqlCheck);
            $stmCheck->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
            $stmCheck->execute();

            $result = $stmCheck->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Team already has a championship of that season
                $sqlCheck1 = "SELECT * FROM Championships WHERE idChampionship = :idChampionship AND season = :season";
                $stmCheck1 = $pdo->prepare($sqlCheck1);
                $stmCheck1->bindParam(':idChampionship', $result['idChampionship'], PDO::PARAM_INT);
                $stmCheck1->bindParam(':season', $season, PDO::PARAM_STR);
                $stmCheck1->execute();

                $result1 = $stmCheck1->fetch(PDO::FETCH_ASSOC);

                if($result1){
                    sendErrorResponse('400', 'Team already has a championship!');
                }
            } 
        }

        $sql = "INSERT INTO Championships (nameChampionship, season, rank, fieldOf, idClub)
                VALUES (:nameChampionship, :season, :rank, :fieldOf, :idClub)";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':nameChampionship', $name, PDO::PARAM_STR);
        $stm->bindParam(':season', $season, PDO::PARAM_STR);
        $stm->bindParam(':rank', $rank, PDO::PARAM_STR);
        $stm->bindParam(':fieldOf', $fieldOf, PDO::PARAM_INT);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->execute();
    
        // Check if there was any error during the execution
        if ($stm === false) {
            sendErrorResponse('400', 'Error inserting');
        }

        // Get the idChampionship to inset in team
        $sqlGet = "SELECT * FROM Championships ORDER BY idChampionship DESC LIMIT 1";
        $stmGet = $pdo->prepare($sqlGet);
        $stmGet->execute();
        $data = $stmGet->fetch(PDO::FETCH_ASSOC);

        $sqlUpdate = "UPDATE Teams SET idChampionship = :idChampionship WHERE idTeam = :idTeam";
        $stmUpdate = $pdo->prepare($sqlUpdate);
        $stmUpdate->bindParam(':idChampionship', $data['idChampionship'], PDO::PARAM_INT);
        $stmUpdate->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stmUpdate->execute();

        // Check if there was any error during the execution
        if ($stmUpdate === false) {
            sendErrorResponse('400', 'Error updating!');
        }
    
        sendSuccessResponse('200', 'Successful registration!', '');
    
    } catch (PDOException $e) {
        // Handle the exception here
        sendErrorResponse('400', 'Error inserting: ' . $e->getMessage());
    }

    sendSuccessResponse('200', 'Successful registration!', '');


} else if($route === 'deleteChampionship'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }

    $idChampionship = isset($requestData['idChampionship']) ? $requestData['idChampionship'] : '';
    $updated = NULL;

    if ($idChampionship == '') {
        sendErrorResponse('400', 'Must have championship introduced!');
    }

    try {
        // Delete the team
        $sqlChamp = "DELETE FROM Championships WHERE idChampionship = :idChampionship";
        $sqlChamp = $pdo->prepare($sqlChamp);
        $sqlChamp->bindParam(':idChampionship', $idChampionship, PDO::PARAM_INT);
        $sqlChamp->execute();
        
        sendSuccessResponse('200', 'Championship deleted successfully!');

    } catch (Exception $e) {
        $message = $e->getMessage();
        sendErrorResponse('400', 'Error deleting championship' . $message);
    }


} else if($route === 'updateChampionship'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    
    $name = isset($requestData['nameChampionship']) ? $requestData['nameChampionship'] : NULL;
    $season = isset($requestData['season']) ? $requestData['season'] : NULL;
    $rank = isset($requestData['rank']) ? $requestData['rank'] : NULL;
    $fieldOf = isset($requestData['fieldOf']) ? $requestData['fieldOf'] : NULL;
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : NULL;
    $idChampionship = isset($requestData['idChampionship']) ? $requestData['idChampionship'] : NULL;

    try {
        $sql = "UPDATE Championships SET nameChampionship = :nameChampionship,
                    season = :season,
                    rank = :rank,
                    fieldOf = :fieldOf,
                    idClub = :idClub
                WHERE idChampionship = :idChampionship";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':nameChampionship', $name, PDO::PARAM_STR);
        $stm->bindParam(':season', $season, PDO::PARAM_STR);
        $stm->bindParam(':rank', $rank, PDO::PARAM_STR);
        $stm->bindParam(':fieldOf', $fieldOf, PDO::PARAM_INT);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->bindParam(':idChampionship', $idChampionship, PDO::PARAM_INT);

        $stm->execute();
    
        sendSuccessResponse('200', 'Championship updated successfully');
    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

    
} else if($route == 'getChampionship'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : NULL;

    $champData = array();

    try {
        $sql = "SELECT * FROM Teams WHERE idTeam = :idTeam";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stm->execute();
        $data = $stm->fetch(PDO::FETCH_ASSOC);

        $sqlC = "SELECT * FROM Championships WHERE idChampionship = :idChampionship";
        $stmC = $pdo->prepare($sqlC);
        $stmC->bindParam(':idChampionship', $data['idChampionship'], PDO::PARAM_INT);
        $stmC->execute();
        $championship = $stmC->fetch(PDO::FETCH_ASSOC);

        $sqlA = "SELECT * FROM AdversaryTeams WHERE idChampionship = :idChampionship";
        $stmA = $pdo->prepare($sqlA);
        $stmA->bindParam(':idChampionship', $data['idChampionship'], PDO::PARAM_INT);
        $stmA->execute();
        $adversaries = $stmA->fetch(PDO::FETCH_ASSOC);        
    
        sendSuccessResponse('200', 'Championship updated successfully', $championship);
    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }
}
?>