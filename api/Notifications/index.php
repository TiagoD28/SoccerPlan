<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

function createNotification($pdo, $type, $idExecuter, $idClub, $idTeam){
    $description = '';
    try {
        $sqlUser = "SELECT * FROM Users WHERE idUser = :idUser";
        $stmUser = $pdo->prepare($sqlUser);
        $stmUser->bindParam(':idUser', $idExecuter, PDO::PARAM_INT);
        $stmUser->execute();
        $user = $stmUser->fetch(PDO::FETCH_ASSOC);

        $sqlClub = "SELECT * FROM Clubs WHERE idClub = :idClub";
        $stmClub = $pdo->prepare($sqlClub);
        $stmClub->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stmClub->execute();
        $club = $stmClub->fetch(PDO::FETCH_ASSOC);

        if(!empty($idTeam)){
            $sqlTeam = "SELECT * FROM Teams WHERE idTeam = :idTeam";
            $stmTeam = $pdo->prepare($sqlTeam);
            $stmTeam->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
            $stmTeam->execute();
            $team = $stmTeam->fetch(PDO::FETCH_ASSOC);
        } else {
            $idTeam = null;
        }  

        switch($type){
            case 'joinClub':
                $description = $user['firstName'] . ' ' . $user['lastName'] . 
                ' send a request to enter in ' . $club['nameClub'];
                break;
            case 'joinTeam':
                $description = $user['firstName'] . ' ' . $user['lastName'] . 
                ' send a request to enter in ' . $team['nameTeam'];
                break;
            case 'joinClubAccepted':
                $description = 'Welcome to ' . $club['nameClub'];
                break;
            case 'joinTeamAccepted':
                $description = 'Welcome to ' . $team['nameTeam'];
                break;
            case 'joinClubRejected':
                $description = 'Request rejected to enter in ' . $club['nameClub'];
                break;
            case 'joinTeamRejected':
                $description = 'Request rejected to enter in ' . $team['nameTeam'];
                break;
            case 'userJoinedClub':
                $description = $user['firstName'] . ' ' . $user['lastName'] . ' entered in ' . $club['nameClub'];
                break;
            case 'userJoinedTeam':
                $description = $user['firstName'] . ' ' . $user['lastName'] . ' entered in ' . $team['nameTeam'];
                break;
            case 'eventAdded':
                // get info of the last event added
                $description = 'Event added for ' . $club['nameClub'] . '!';
                break;
            // case 'codeClubGenerated':
            //     // get info of the last event added
            //     $description = 'Code generated to enter ' . $club['nameClub'] . ': ' . $code . '!';
            //     break;
            // case 'codeClub':
            //     // get info of the last event added
            //     $description = 'Code to enter ' . $club['nameClub'] . ': ' . $code . '!';
            //     break;
        }

        $sql = "INSERT INTO Notifications (typeNotification, descriptionN, timeExecuted, idExecuter, idClub, idTeam)
        VALUES (:typeNotification, :descriptionN, NOW(), :idExecuter, :idClub, :idTeam)";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':typeNotification', $type, PDO::PARAM_STR);
        $stm->bindParam(':descriptionN', $description, PDO::PARAM_STR); 
        $stm->bindParam(':idExecuter', $idExecuter, PDO::PARAM_STR);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT); 
        $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stm->execute();
    
        // Check if there was any error during the execution
        if ($stm === false) {
            sendErrorResponse('400', 'Error inserting notification!');
        }        
    
    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }
}


if($route === 'getNotifications'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : NULL;

    try {
        $sql = "SELECT * FROM Notifications WHERE idClub = :idClub";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->execute();
        $notifications = $stm->fetchAll(PDO::FETCH_ASSOC);

        if(!$notifications){
            sendSuccessResponse('200', 'Club doesn\'t have notifications yet!');
        }
    
        sendSuccessResponse('200', 'Notifications', $notifications);
    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    } 
}

?>