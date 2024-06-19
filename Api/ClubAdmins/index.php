<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

if($route == 'answerRequestOfCoachToClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    // $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    // // $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    // $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    // $content = 'joinClub';

    //Figure it out how to get the idUser and typeNotification
    // $status = isset($requestData['status']) ? $requestData['status'] : '';

    // $status = 'pending';
    // $idUser = 2;
    // $typeNotification = 'joinClub';
    // $isRead = 1;
    // $isClub = 2;

    // if($idClub === ''){
    //     sendErrorResponse('410', 'You must select Club to send request!');
    // }
    // if($idCoach === ''){
    //     sendErrorResponse('420', 'You must insert the Code!');
    // }

    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $typeNotification = isset($requestData['typeNotification']) ? $requestData['typeNotification'] : '';
    $isRead = isset($requestData['isRead']) ? $requestData['isRead'] : '';
    $status = isset($requestData['status']) ? $requestData['status'] : '';

    // get the idCoach of idUser
    $sql = "SELECT idCoach FROM Coaches WHERE idUser = :idUser";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stm->execute();

    $data = $stm->fetch(PDO::FETCH_ASSOC);

    if($data == false){
        sendErrorResponse('400', 'There is no User with this idCoach!');
    }

    $idCoach = $data["idCoach"];
    echo $idCoach;


    $newStatus = 'accepted';
    $newIsRead = 1;

    // // update the notification
    $sqlUpdate = "UPDATE Notifications SET statee = :newStatus, isRead = :newIsRead WHERE (idUser = :idUser AND typeNotification = :typeNotification) AND (isRead = :isRead AND idRelated = :idClub) AND statee = :statuss ";
    $stmUpdate = $pdo ->prepare($sqlUpdate);
    $stmUpdate->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
    $stmUpdate->bindParam(':newIsRead', $newIsRead, PDO::PARAM_INT);
    $stmUpdate->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stmUpdate->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    $stmUpdate->bindParam(':typeNotification', $typeNotification, PDO::PARAM_STR);
    $stmUpdate->bindParam(':isRead', $isRead, PDO::PARAM_INT);
    $stmUpdate->bindParam(':statuss', $status, PDO::PARAM_STR);
    $success = $stmUpdate->execute();

    

    if ($success === false) {
        // An error occurred
        $errorInfo = $stmUpdate->errorInfo();
        echo $errorInfo;
        sendErrorResponse('500', 'Error updating the value of column idClub table Coaches: ' . $errorInfo[2]);
    } else {
        // Check if any rows were affected
        $rowCount = $stmUpdate->rowCount();
        
        if ($rowCount === 0) {
            // No rows were updated, consider it an error
            sendErrorResponse('404', 'No matching rows found for the update criteria');
        } else {
            // Rows were updated successfully
            // update the table of coach and set as member of the club
            $newIdClub = $idClub;
            $sqlUpdate1 = "UPDATE Coaches SET idClub = :newIdClub WHERE idCoach = :idCoach";
            $stmUpdate1 = $pdo ->prepare($sqlUpdate1);
            $stmUpdate1->bindParam(':newIdClub', $newIdClub, PDO::PARAM_INT);
            $stmUpdate1->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
            $success1 = $stmUpdate1->execute();

            if ($success1 === false) {
                // An error occurred
                $errorInfo = $stmUpdate1->errorInfo();
                echo $errorInfo;
                sendErrorResponse('500', 'Error updating the value of column idClub table Coaches: ' . $errorInfo[2]);
            }else {
                // Check if any rows were affected
                $rowCount1 = $stmUpdate1->rowCount();
                
                if ($rowCount1 === 0) {
                    // No rows were updated, consider it an error
                    sendErrorResponse('404', 'No matching rows found in table Coaches');
                } else {
                    // Rows were updated successfully        
                    if ($success1 === false) {
                        // An error occurred
                        $errorInfo = $stmUpdate1->errorInfo();
                        echo $errorInfo;
                        sendErrorResponse('500', 'Error updating the value of column idClub table Coaches: ' . $errorInfo[2]);
                    }
            
            sendSuccessResponse('200', 'Coach accepted to join the club!', '');
        }
    }
}
}

    // if ($stmUpdate === false) {
    //     // An error occurred
    //     $errorInfo = $stmUpdate->errorInfo();
    //     echo $errorInfo;
    //     sendErrorResponse('500', 'Error updating the value of column idClub table Coaches: ' . $errorInfo[2]);
    // }

    // sendSuccessResponse('200', 'Coach accepted to join the club!', '');
} else if($route == 'answerRequestOfCoachToTeam'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    // $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    // // $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    // $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    // $content = 'joinClub';

    //Figure it out how to get the idUser and typeNotification
    // $status = isset($requestData['status']) ? $requestData['status'] : '';

    // $status = 'pending';
    // $idUser = 2;
    // $typeNotification = 'joinClub';
    // $isRead = 1;
    // $isClub = 2;

    // if($idClub === ''){
    //     sendErrorResponse('410', 'You must select Club to send request!');
    // }
    // if($idCoach === ''){
    //     sendErrorResponse('420', 'You must insert the Code!');
    // }

    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $typeNotification = isset($requestData['typeNotification']) ? $requestData['typeNotification'] : '';
    $isRead = isset($requestData['isRead']) ? $requestData['isRead'] : '';
    $status = isset($requestData['status']) ? $requestData['status'] : '';

    // get the idCoach of idUser
    $sql = "SELECT idCoach FROM Coaches WHERE idUser = :idUser";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stm->execute();

    $data = $stm->fetch(PDO::FETCH_ASSOC);

    if($data == false){
        sendErrorResponse('400', 'There is no User with this idCoach!');
    }

    $idCoach = $data["idCoach"];
    // echo $idCoach;


    $newStatus = 'accepted';
    $newIsRead = 1;

    echo $newStatus;

    // // update the notification
    $sqlUpdate = "UPDATE Notifications SET statee = :newStatus, isRead = :newIsRead WHERE (idUser = :idUser AND typeNotification = :typeNotification) AND (isRead = :isRead AND idRelated = :idTeam) AND statee = :statuss ";
    $stmUpdate = $pdo ->prepare($sqlUpdate);
    $stmUpdate->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
    $stmUpdate->bindParam(':newIsRead', $newIsRead, PDO::PARAM_INT);
    $stmUpdate->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stmUpdate->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
    $stmUpdate->bindParam(':typeNotification', $typeNotification, PDO::PARAM_STR);
    $stmUpdate->bindParam(':isRead', $isRead, PDO::PARAM_INT);
    $stmUpdate->bindParam(':statuss', $status, PDO::PARAM_STR);
    $success = $stmUpdate->execute();

    

    if ($success === false) {
        // An error occurred
        $errorInfo = $stmUpdate->errorInfo();
        echo $errorInfo;
        sendErrorResponse('500', 'Error updating the value of column idTeam table Coaches: ' . $errorInfo[2]);
    } else {
        // Check if any rows were affected
        $rowCount = $stmUpdate->rowCount();
        
        if ($rowCount === 0) {
            // No rows were updated, consider it an error
            sendErrorResponse('404', 'No matching rows found for the update criteria');
        } else {
            // Rows were updated successfully
            // update the table team and set as coach of the team
            // $newIdCoach = $idCoach;
            $sqlUpdate1 = "UPDATE Teams SET idCoach = :idCoach WHERE idTeam = :idTeam";
            $stmUpdate1 = $pdo ->prepare($sqlUpdate1);
            $stmUpdate1->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
            $stmUpdate1->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
            $success1 = $stmUpdate1->execute();

            if ($success1 === false) {
                // An error occurred
                $errorInfo = $stmUpdate1->errorInfo();
                echo $errorInfo;
                sendErrorResponse('500', 'Error updating the value of column idTeam table Coaches: ' . $errorInfo[2]);
            }else {
                // Check if any rows were affected
                $rowCount1 = $stmUpdate1->rowCount();
                
                if ($rowCount1 === 0) {
                    // No rows were updated, consider it an error
                    sendErrorResponse('404', 'No matching rows found in table Coaches');
                } else {
                    // Rows were updated successfully        
                    if ($success1 === false) {
                        // An error occurred
                        $errorInfo = $stmUpdate1->errorInfo();
                        echo $errorInfo;
                        sendErrorResponse('500', 'Error updating the value of column idTeam table Coaches: ' . $errorInfo[2]);
                    }
                sendSuccessResponse('200', 'Coach accepted to join the team!', '');
                }
            }
        }
    }
} else if($route == 'getClubWeb'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    
    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';

    if(isset($requestData['idClubAdmin'])){
        $idClubAdmin = $requestData['idClubAdmin'];

        $sql = "SELECT * FROM Clubs WHERE idClubAdmin = :idClubAdmin";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':idClubAdmin', $idClubAdmin, PDO::PARAM_STR);
        $stm->execute();
        // $result = $stm->execute();
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) == 0) {
            // No clubs found, send error response
            echo $idClubAdmin;
            sendErrorResponse('404', 'Club Administrator doesn t have a club!');
        }

        sendSuccessResponse('200', 'Success', $data);
    } else if(isset($requestData['idEmployer'])){
        // $idEmployer = $requestData['idEmployer'];
        $idClub = $requestData['idClub'];

        $sql = "SELECT * FROM Clubs WHERE idClub = :idClub";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_STR);
        $stm->execute();
        // $result = $stm->execute();
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) == 0) {
            // No clubs found, send error response
            sendErrorResponse('400', 'Employer doesn t have a club!');
        }

        sendSuccessResponse('200', 'Success', $data);
    }

    // echo $requestData['id'];
    // echo $idAdmin;
}