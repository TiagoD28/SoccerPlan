<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

if($route == 'sendRequestClub'){

} else if($route == 'sendCodeClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $code = isset($requestData['code']) ? $requestData['code'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idPlayer = isset($requestData['idPlayer']) ? $requestData['idPlayer'] : '';
    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    // $content = 'Welcome to the Team!';
    // $dateTimeSended = isset($requestData['dateTimeSended']) ? $requestData['dateTimeSended'] : '';
    // $dateTimeSended = '2023-11-23 20:21:55';
    // $typeNotification = 'joinClub';
    // $idRelated = $idTeam;
    $status = 'accepted';
    $used = 0;

    if($idClub === ''){
        sendErrorResponse('410', 'You must select Club to join!');
    }
    if($code === ''){
        sendErrorResponse('420', 'You must insert the Code!');
    }
    if($idUser === ''){
        sendErrorResponse('420', 'You must insert the idUser!');
    }
    if($idCoach === ''){
        if($idPlayer === ''){
            sendErrorResponse('400', 'Must have id!');
        }
    }

    // check if code exists
    $sql = "SELECT * FROM clubscodes WHERE (randomCode = :code AND used = :used) AND (idClub = :idClub AND idUser = :idUser)";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':code', $code, PDO::PARAM_STR);
    $stm->bindParam(':used', $used, PDO::PARAM_INT);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stm->execute();

    $data = $stm->fetch(PDO::FETCH_ASSOC);

    if($data == false){
        sendErrorResponse('400', 'Invalid code or code already used or club doesn t have this code!');
    }

    // update de value of used(used - 0 = (false-> code never used) - 1 (true-> code already used))
    $updateValue = 1;
    $sqlUpdate = "UPDATE clubscodes SET used = :updateValue WHERE randomCode = :code";
    $stmUpdate = $pdo->prepare($sqlUpdate);
    $stmUpdate->bindParam(':updateValue', $updateValue, PDO::PARAM_INT);
    $stmUpdate->bindParam(':code', $code, PDO::PARAM_STR);
    $stmUpdate->execute();

    if ($stmUpdate === false) {
        // An error occurred
        $errorInfo = $stmUpdate->errorInfo();
        echo $errorInfo;
        sendErrorResponse('500', 'Error updating the value of column used table clubscodes: ' . $errorInfo[2]);
    }


    if($idCoach != ''){
        // update the idCoach of the team
        $sqlUpdate1 = "UPDATE Coaches SET idClub = :idClub WHERE idCoach = :idCoach";
        $stmUpdate1 = $pdo ->prepare($sqlUpdate1);
        $stmUpdate1->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stmUpdate1->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
        $stmUpdate1->execute();

        if ($stmUpdate1 === false) {
            // An error occurred
            $errorInfo = $stmUpdate1->errorInfo();
            echo $errorInfo;
            sendErrorResponse('500', 'Error updating the value of column idClub table Coaches: ' . $errorInfo[2]);
        }
    } 

    if($idPlayer != ''){
        // update the idCoach of the team
        $sqlUpdate2 = "UPDATE Players SET idClub = :idClub WHERE idPlayer = :idPlayer";
        $stmUpdate2 = $pdo ->prepare($sqlUpdate2);
        $stmUpdate2->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stmUpdate2->bindParam(':idPlayer', $idPlayer, PDO::PARAM_INT);
        $stmUpdate2->execute();

        if ($stmUpdate2 === false) {
            // An error occurred
            $errorInfo = $stmUpdate2->errorInfo();
            echo $errorInfo;
            sendErrorResponse('500', 'Error updating the value of column Players table Teams: ' . $errorInfo[2]);
        }
    }

    sendSuccessResponse('200', 'Inserted code valid! Welcome to the Team!');

} else if($route == 'sendCodeTeam'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $code = isset($requestData['code']) ? $requestData['code'] : '';
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idPlayer = isset($requestData['idPlayer']) ? $requestData['idPlayer'] : '';
    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    $content = 'Welcome to the Team!';
    // $dateTimeSended = isset($requestData['dateTimeSended']) ? $requestData['dateTimeSended'] : '';
    $dateTimeSended = '2023-11-23 20:21:55';
    $typeNotification = 'joinTeam';
    $idRelated = $idTeam;
    $status = 'accepted';
    $used = 0;

    if($idTeam === ''){
        sendErrorResponse('410', 'You must select Team to join!');
    }
    if($code === ''){
        sendErrorResponse('420', 'You must insert the Code!');
    }
    if($idUser === ''){
        sendErrorResponse('420', 'You must insert the idUser!');
    }
    if($idCoach === ''){
        if($idPlayer === ''){
            sendErrorResponse('400', 'Must have id!');
        }
    }

    // check if code exists
    $sql = "SELECT * FROM teamscodes WHERE (randomCode = :code AND used = :used) AND (idTeam = :idTeam)";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':code', $code, PDO::PARAM_STR);
    $stm->bindParam(':used', $used, PDO::PARAM_INT);
    $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
    $stm->execute();

    $data = $stm->fetch(PDO::FETCH_ASSOC);

    if($data == false){
        sendErrorResponse('400', 'Invalid code or code already used or team doesn t have this code!');
    }

    // update de value of used(used - 0 = (false-> code never used) - 1 (true-> code already used))
    $updateValue = 1;
    $sqlUpdate = "UPDATE teamscodes SET used = :updateValue WHERE randomCode = :code AND idUser = :idUser";
    $stmUpdate = $pdo->prepare($sqlUpdate);
    $stmUpdate->bindParam(':updateValue', $updateValue, PDO::PARAM_INT);
    $stmUpdate->bindParam(':code', $code, PDO::PARAM_STR);
    $stmUpdate->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stmUpdate->execute();

    if ($stmUpdate === false) {
        // An error occurred
        $errorInfo = $stmUpdate->errorInfo();
        echo $errorInfo;
        sendErrorResponse('500', 'Error updating the value of column used table clubscodes: ' . $errorInfo[2]);
        exit;
    }


    if($idCoach != ''){
        // update the idCoach of the team
        $sqlUpdate1 = "UPDATE Teams SET idCoach = :idCoach WHERE idTeam = :idTeam";
        $stmUpdate1 = $pdo ->prepare($sqlUpdate1);
        $stmUpdate1->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
        $stmUpdate1->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stmUpdate1->execute();

        if ($stmUpdate1 === false) {
            // An error occurred
            $errorInfo = $stmUpdate1->errorInfo();
            echo $errorInfo;
            sendErrorResponse('500', 'Error updating the value of column idCoach table Teams: ' . $errorInfo[2]);
        }
    } 

    if($idPlayer != ''){
        // update the idCoach of the team
        $sqlUpdate2 = "UPDATE Players SET idTeam = :idTeam WHERE idPlayer = :idPlayer";
        $stmUpdate2 = $pdo ->prepare($sqlUpdate2);
        $stmUpdate2->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stmUpdate2->bindParam(':idPlayer', $idPlayer, PDO::PARAM_INT);
        $stmUpdate2->execute();

        if ($stmUpdate2 === false) {
            // An error occurred
            $errorInfo = $stmUpdate2->errorInfo();
            echo $errorInfo;
            sendErrorResponse('500', 'Error updating the value of column Players table Teams: ' . $errorInfo[2]);
        }
    }
    

    // create notification to user know that he entered in the club
    // $sqlNotf = "INSERT INTO Notifications (content, timeSended, typeNotification, idRelated, statuss, idUser)
    // VALUES (:content, :timeSend, :typeNotification, :idRelated, :statuss, :idUser)";
    // $stmInsert = $pdo->prepare($sqlNotf);
    // $stmInsert->bindParam(':content', $content, PDO::PARAM_STR);
    // $stmInsert->bindParam(':timeSend', $dateTimeSended, PDO::PARAM_STR);
    // // $stmInsert->bindParam(':isRead', $isRead, PDO::PARAM_STR);
    // $stmInsert->bindParam(':typeNotification', $typeNotification, PDO::PARAM_STR);
    // $stmInsert->bindParam(':idRelated', $idRelated, PDO::PARAM_INT);
    // $stmInsert->bindParam(':statuss', $status, PDO::PARAM_STR);
    // $stmInsert->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    // $stmInsert->execute();

    // if ($stmInsert === false) {
    //     // An error occurred
    //     $errorInfo = $stmInsert->errorInfo();
    //     echo $errorInfo;
    //     sendErrorResponse('500', 'Error inserting the values: ' . $errorInfo[2]);
    // }


    sendSuccessResponse('200', 'Inserted code valid! Welcome to the Team!', $data);
    
}