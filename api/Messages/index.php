<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

if($route == 'sendMessage'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $idSender = isset($requestData['idSender']) ? $requestData['idSender'] : '';
    $content = isset($requestData['content']) ? $requestData['content'] : '';

    if($idTeam == ''){
        sendErrorResponse('400-1', 'Name must be introduced!'); 
    }
    if($idSender == ''){
        sendErrorResponse('400-2', 'Founded Year must be introduced!');
    }
    if($content == ''){
        sendErrorResponse('400-3', 'City must be introduced!');
    }
    
    $sql = "INSERT INTO ChatMessages (idTeam, idSender, content)
    VALUES (:idTeam, :idSender, :content)";
    $stm = $pdo ->prepare($sql);
    $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
    $stm->bindParam(':idSender', $idSender, PDO::PARAM_INT);
    $stm->bindParam(':content', $content, PDO::PARAM_STR);
    $stm->execute();

    sendSuccessResponse('200', 'Successful registration!', '');

} else if($route == 'getMessages'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $messages = [];

    if($idTeam == ''){
        sendErrorResponse('400-1', 'Name must be introduced!'); 
    }

    try{
        $sql = "SELECT * FROM ChatMessages WHERE idTeam = :idTeam";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stm->execute();
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);
        
        if($data == 0){
            sendSuccessResponse('200', 'There is no messages!', $data);    
        }
        
        foreach($data as $message){
            $sqlUser = "SELECT * FROM Users WHERE idUser = :idUser";
            $stmUser = $pdo->prepare($sqlUser);
            $stmUser->bindParam(':idUser', $message['idSender'], PDO::PARAM_INT);
            $stmUser->execute();
            $userData = $stmUser->fetch(PDO::FETCH_ASSOC);

            $tableName = '';
            if($userData['typeUser'] == 'Player'){
                $tableName = 'Players';
            } else {
                $tableName = 'Coaches';
            }

            $sqlInfo = "SELECT * FROM $tableName WHERE idUser = :idUser";
            $stmInfo = $pdo->prepare($sqlInfo);
            $stmInfo->bindParam(':idUser', $message['idSender'], PDO::PARAM_INT);
            $stmInfo->execute();
            $userInfo = $stmInfo->fetch(PDO::FETCH_ASSOC);

            if($userInfo['img'] != null){
                $userInfo['img'] = base64_decode($userInfo['img']);
            }

            $messages[] = array_merge($message, $userInfo);
        }
        sendSuccessResponse('200', 'Success', $messages);

    } catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

}