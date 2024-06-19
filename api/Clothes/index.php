<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

if($route === 'getClothes'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';

    if ($idClub == '') {
        sendErrorResponse('400', 'Must have club introduced!');
    }

    try{
        $sql = "SELECT * FROM Clothes WHERE idClub = :idClub";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->execute();
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as &$row) { // &row allows to modify the values of the array directly within the loop.
            $row['img'] = base64_encode($row['img']);
        }
    } catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

    sendSuccessResponse('200', 'Success', $data);

} else if($route == 'createClothe'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    
    $name = isset($requestData['nameClothe']) ? $requestData['nameClothe'] : NULL;
    $img = isset($requestData['img']) ? $requestData['img'] : NULL;
    $season = isset($requestData['season']) ? $requestData['season'] : NULL;
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : NULL;

    try {
        $sql = "INSERT INTO Clothes (nameClothe, season, img, idClub)
                VALUES (:nameClothe, :season, :img, :idClub)";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':nameClothe', $name, PDO::PARAM_STR);
        $stm->bindParam(':season', $season, PDO::PARAM_STR);
        $stm->bindParam(':img', $img, PDO::PARAM_LOB);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->execute();
    
        // Check if there was any error during the execution
        if ($stm->errorCode() !== '00000') {
            $errorInfo = $stm->errorInfo();
            sendErrorResponse('400', 'Error inserting: ' . $errorInfo[2]);
        }
    
        sendSuccessResponse('200', 'Clothe created successfuly!', '');
    
    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

} else if($route === 'updateClothe'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    
    $idClothe = isset($requestData['idClothe']) ? $requestData['idClothe'] : NULL;
    $name = isset($requestData['nameClothe']) ? $requestData['nameClothe'] : NULL;
    $season = isset($requestData['season']) ? $requestData['season'] : NULL;
    $img = isset($requestData['img']) ? $requestData['img'] : NULL;
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : NULL;

    try {
        if(!empty($img)){
            $sql = "UPDATE Clothes SET 
                        nameClothe = :nameClothe,
                        season = :season,
                        img = :img
                    WHERE idClothe = :idClothe";
            $stm = $pdo->prepare($sql);
            $stm->bindParam(':nameClothe', $name, PDO::PARAM_STR);
            $stm->bindParam(':season', $season, PDO::PARAM_STR);
            $stm->bindParam(':img', $img, is_null($img) ? PDO::PARAM_NULL : PDO::PARAM_LOB);
            $stm->bindParam(':idClothe', $idClothe, PDO::PARAM_INT);
    
            $stm->execute();
    
            if ($stm->errorCode() !== '00000') {
                $errorInfo = $stm->errorInfo();
                sendErrorResponse('400', 'Error: ' . $errorInfo);
            }

        } else{
            $sql = "UPDATE Clothes SET 
                        nameClothe = :nameClothe,
                        season = :season
                    WHERE idClothe = :idClothe";
            $stm = $pdo->prepare($sql);
            $stm->bindParam(':nameClothe', $name, PDO::PARAM_STR);
            $stm->bindParam(':season', $season, PDO::PARAM_STR);
            $stm->bindParam(':idClothe', $idClothe, PDO::PARAM_INT);
    
            $stm->execute();
    
            if ($stm->errorCode() !== '00000') {
                $errorInfo = $stm->errorInfo();
                sendErrorResponse('400', 'Error: ' . $errorInfo);
            }
        }
    
        sendSuccessResponse('200', 'Clothe updated successfully!');
    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

} else if($route === 'deleteClothe'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }

    $idClothe = isset($requestData['idClothe']) ? $requestData['idClothe'] : '';

    if ($idClothe == '') {
        sendErrorResponse('400', 'Must have clothe selected!');
    }

    try {
        // Delete the team
        $sql = "DELETE FROM Clothes WHERE idClothe = :idClothe";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idClothe', $idClothe, PDO::PARAM_INT);
        $stm->execute();
        
        sendSuccessResponse('200', 'Clothe deleted successfully!');
    } catch (Exception $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }


}