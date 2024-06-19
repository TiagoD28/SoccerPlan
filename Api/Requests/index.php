<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

function clubExists($idClub, $pdo){
    $sql = "SELECT * FROM Clubs WHERE idClub = :idClub";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_STR);
    $stm->execute();

    $count = $stm->fetchColumn();

    return $count > 0;
}

if($route == 'sendRequestByCoachToEnterClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        echo $requestData;
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    // check if the coach already made a request and the admin answered

    // $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $content = isset($requestData['content']) ? $requestData['content'] : '';
    $dateTimeSended = isset($requestData['dateTimeSended']) ? $requestData['dateTimeSended'] : '';
    $typeNotification = isset($requestData['typeNotification']) ? $requestData['typeNotification'] : '';
    $idRelated = $idClub;

    if($idUser === ''){
        sendErrorResponse('410', 'Id user it s empty!');
    }
    if($idClub === ''){
        sendErrorResponse('410', 'Id club it s empty!');
    }
    if($idCoach === ''){
        sendErrorResponse('410', 'Id coach it s empty!');
    }
    if($content === ''){
        sendErrorResponse('410', 'Content it s empty!');
    }
    if($idClub === ''){
        sendErrorResponse('410', 'Id user it s empty!');
    }
    

    // create notification to user know that he entered in the club
    $sqlNotf = "INSERT INTO Notifications (content, timeSended, typeNotification, idRelated, idUser)
    VALUES (:content, :timeSend, :typeNotification, :idRelated, :idUser)";
    $stmInsert = $pdo->prepare($sqlNotf);
    $stmInsert->bindParam(':content', $content, PDO::PARAM_STR);
    $stmInsert->bindParam(':timeSend', $dateTimeSended, PDO::PARAM_STR);
    $stmInsert->bindParam(':typeNotification', $typeNotification, PDO::PARAM_STR);
    $stmInsert->bindParam(':idRelated', $idRelated, PDO::PARAM_INT);
    $stmInsert->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stmInsert->execute();

    if ($stmInsert === false) {
        // An error occurred
        $errorInfo = $stmInsert->errorInfo();
        echo $errorInfo;
        sendErrorResponse('500', 'Error inserting the values: ' . $errorInfo[2]);
    }

    sendSuccessResponse('200', 'Request sended successfuly!', '');
} else if($route == 'sendRequestByCoachToEnterTeam'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        echo $requestData;
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    // check if the coach already made a request and the admin answered

    // $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $content = isset($requestData['content']) ? $requestData['content'] : '';
    $dateTimeSended = isset($requestData['dateTimeSended']) ? $requestData['dateTimeSended'] : '';
    $typeNotification = isset($requestData['typeNotification']) ? $requestData['typeNotification'] : '';
    $idRelated = $idTeam;

    if($idUser === ''){
        sendErrorResponse('410', 'Id user it s empty!');
    }
    if($idTeam === ''){
        sendErrorResponse('410', 'Id team it s empty!');
    }
    if($idCoach === ''){
        sendErrorResponse('410', 'Id coach it s empty!');
    }
    if($content === ''){
        sendErrorResponse('410', 'Content it s empty!');
    }
    if($typeNotification === ''){
        sendErrorResponse('410', 'Id user it s empty!');
    }
    

    // create notification to user know that he entered in the club
    $sqlNotf = "INSERT INTO Notifications (content, timeSended, typeNotification, idRelated, idUser)
    VALUES (:content, :timeSend, :typeNotification, :idRelated, :idUser)";
    $stmInsert = $pdo->prepare($sqlNotf);
    $stmInsert->bindParam(':content', $content, PDO::PARAM_STR);
    $stmInsert->bindParam(':timeSend', $dateTimeSended, PDO::PARAM_STR);
    $stmInsert->bindParam(':typeNotification', $typeNotification, PDO::PARAM_STR);
    $stmInsert->bindParam(':idRelated', $idRelated, PDO::PARAM_INT);
    $stmInsert->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stmInsert->execute();

    if ($stmInsert === false) {
        // An error occurred
        $errorInfo = $stmInsert->errorInfo();
        echo $errorInfo;
        sendErrorResponse('500', 'Error inserting the values: ' . $errorInfo[2]);
    }

    sendSuccessResponse('200', 'Request sended successfuly!', '');


} else if($route == 'getRequestsClub') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        echo $requestData;
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    // $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';

    if($idClub === ''){
        sendErrorResponse('400', 'Id club it s empty!');
    }
    

    // create notification to user know that he entered in the club
    $sql = "SELECT * FROM RequestsToClub WHERE idClub = :idClub";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_STR);
    $stm->execute();

    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    if(count($data) == 0){
        sendErrorResponse('400', 'Doesn t exist requests to Club!');
    } else {
        sendSuccessResponse('200', 'Success', $data);   
    }


} else if($route == 'getRequestsTeam') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        echo $requestData;
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    // $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';

    if($idClub === ''){
        sendErrorResponse('400', 'Id club it s empty!');
    }
    

    // create notification to user know that he entered in the club
    $sql = "SELECT * FROM RequestsToTeam WHERE idClub = :idClub";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_STR);
    $stm->execute();

    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    if(count($data) == 0){
        sendErrorResponse('400', 'Doesn t exist requests to Teams   !');
    } else {
        sendSuccessResponse('200', 'Success', $data);   
    }


} else if($route == 'sendRequestClub'){
    
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        echo $requestData;
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    // check if the coach already made a request and the admin answered

    // $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idRequester = isset($requestData['idRequester']) ? $requestData['idRequester'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $state = 'pending';

    if($idRequester === ''){
        sendErrorResponse('400', 'Id user it s empty!');
    }
    if($idClub === ''){
        sendErrorResponse('400', 'Id club it s empty!');
    }
    
    // create notification to user know that he entered in the club
    $sql = "INSERT INTO RequestsToClub (statee, idRequester, idClub)
    VALUES (:statee, :idRequester, :idClub)";
    $stmInsert = $pdo->prepare($sql);
    $stmInsert->bindParam(':idRequester', $idRequester, PDO::PARAM_INT);
    $stmInsert->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    $stmInsert->bindParam(':statee', $state, PDO::PARAM_STR);
    $stmInsert->execute();

    if ($stmInsert === false) {
        // An error occurred
        $errorInfo = $stmInsert->errorInfo();
        echo $errorInfo;
        sendErrorResponse('400', 'Error inserting the values: ' . $errorInfo[2]);
    }

    sendSuccessResponse('200', 'Request sended to club successfuly!', '');



} else if($route == 'sendRequestTeam'){

    // $requestData = json_decode(file_get_contents("php://input"), true);
    // if ($requestData === null) {
    //     echo $requestData;
    //     // Handle JSON decoding error
    //     sendErrorResponse('400', 'Invalide JSON data');
    //     exit;
    // }

    // // check if the coach already made a request and the admin answered

    // // $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    // $idRequester = isset($requestData['idRequester']) ? $requestData['idRequester'] : '';
    // $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    // $state = 'pending';

    // if($idRequester === ''){
    //     sendErrorResponse('400', 'Id user it s empty!');
    // }
    // if($idClub === ''){
    //     sendErrorResponse('400', 'Id club it s empty!');
    // }
    
    // // create notification to user know that he entered in the club
    // $sql = "INSERT INTO RequestsToClub (statee, idRequester, idClub)
    // VALUES (:statee, :idRequester, :idClub)";
    // $stmInsert = $pdo->prepare($sql);
    // $stmInsert->bindParam(':idRequester', $idRequester, PDO::PARAM_INT);
    // $stmInsert->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    // $stmInsert->bindParam(':statee', $state, PDO::PARAM_STR);
    // $stmInsert->execute();

    // if ($stmInsert === false) {
    //     // An error occurred
    //     $errorInfo = $stmInsert->errorInfo();
    //     echo $errorInfo;
    //     sendErrorResponse('400', 'Error inserting the values: ' . $errorInfo[2]);
    // }

    // sendSuccessResponse('200', 'Request sended to club successfuly!', '');

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        echo $requestData;
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    // check if the coach already made a request and the admin answered

    // $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idRequester = isset($requestData['idRequester']) ? $requestData['idRequester'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $state = 'pending';

    if($idRequester === ''){
        sendErrorResponse('400', 'Id user it s empty!');
    }
    if($idClub === ''){
        sendErrorResponse('400', 'Id club it s empty!');
    }
    if($idTeam === ''){
        sendErrorResponse('400', 'Id club it s empty!');
    }
    
    // create notification to user know that he entered in the club
    $sql = "INSERT INTO RequestsToTeam (statee, idRequester, idClub, idTeam)
    VALUES (:statee, :idRequester, :idClub, :idTeam)";
    $stmInsert = $pdo->prepare($sql);
    $stmInsert->bindParam(':idRequester', $idRequester, PDO::PARAM_INT);
    $stmInsert->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    $stmInsert->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
    $stmInsert->bindParam(':statee', $state, PDO::PARAM_STR);
    $stmInsert->execute();

    if ($stmInsert === false) {
        // An error occurred
        $errorInfo = $stmInsert->errorInfo();
        echo $errorInfo;
        sendErrorResponse('400', 'Error inserting the values: ' . $errorInfo[2]);
    }

    sendSuccessResponse('200', 'Request sended to team successfuly!', '');


} else if($route == 'answerRequestClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        echo $requestData;
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    // check if the coach already made a request and the admin answered

    // $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idRequestClub = isset($requestData['idRequestClub']) ? $requestData['idRequestClub'] : '';
    $idRequester = isset($requestData['idRequester']) ? $requestData['idRequester'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $state = isset($requestData['state']) ? $requestData['state'] : '';
    $pending = 'pending'; // only update table if request its pending

    if($idRequestClub === ''){
        sendErrorResponse('400', 'Id Request Club it s empty!');
    }
    if($idRequester === ''){
        sendErrorResponse('400', 'Id Requester it s empty!');
    }
    if($idClub === ''){
        sendErrorResponse('400', 'Id Club it s empty!');
    }

    try {    

        $sql = "UPDATE requeststoclub SET statee = :statee WHERE (idRequestClub = :idRequestClub && statee = :pending)";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idRequestClub', $idRequestClub, PDO::PARAM_INT);
        $stm->bindParam(':statee', $state, PDO::PARAM_STR);
        $stm->bindParam(':pending', $pending, PDO::PARAM_STR); // this is to check if request its pending
        $stm->execute();

        $rowCount = $stm->rowCount(); // Get the number of affected rows

        if ($rowCount === false) {
            // There was an error retrieving the row count
            sendErrorResponse('400', 'Error checking the number of affected rows in requeststoclub!');
        } elseif ($rowCount === 0) {
            // No rows were updated, which means the condition didn't match any records
            sendErrorResponse('400', 'No matching records found for the update condition in requeststoclub!');
        }


        // only update tablename where the answer its accepted
        if($state == 'accepted'){
            $sqlSelect = "SELECT * FROM Users WHERE idUser = :idUser";
            $stmSelect = $pdo->prepare($sqlSelect);
            $stmSelect->bindParam(':idUser', $idRequester, PDO::PARAM_INT);
            $stmSelect->execute();
        
            $data = $stmSelect->fetch(PDO::FETCH_ASSOC);
            // echo $data['typeUser'];

            if($data != 0){
                if($data['typeUser'] == 'Employer'){
                    $tablename = 'employers';
                    $id = 'idEmployer';
                } else if($data['typeUser'] == 'ClubAdmin'){
                    $tablename = 'clubadmins';
                    $id = 'idClubAdmin';
                } else if($data['typeUser'] == 'Coach'){
                    $tablename = 'coaches';
                    $id = 'idCoach';
                } else if($data['typeUser'] == 'Player'){
                    $tablename = 'players';
                    $id = 'idPlayer';
                }

                // get the id of the table tablename
                $sqlSelect1 = "SELECT * FROM $tablename WHERE idUser = :idUser";
                $stmSelect1 = $pdo->prepare($sqlSelect1);
                $stmSelect1->bindParam(':idUser', $idRequester, PDO::PARAM_INT);
                $stmSelect1->execute();
            
                $data1 = $stmSelect1->fetch(PDO::FETCH_ASSOC);

                if($data1 != 0){
                    if($data['typeUser'] == 'Employer'){
                        $value = $data1['idEmployer'];
                    } else if($data['typeUser'] == 'ClubAdmin'){
                        $value = $data1['idClubAdmin'];
                    } else if($data['typeUser'] == 'Coach'){
                        $value = $data1['idCoach'];
                    } else if($data['typeUser'] == 'Player'){
                        $value = $data1['idPlayer'];
                        // echo $value;
                    }
                }
                // end 
                // echo $idClub;
                
                // echo $id;

                $sqlUpdate = "UPDATE $tablename SET idClub = :idClub WHERE $id = :id";
                $stmUpdate = $pdo->prepare($sqlUpdate);
                $stmUpdate->bindParam(':idClub', $idClub, PDO::PARAM_INT);
                $stmUpdate->bindParam(':id', $value, PDO::PARAM_STR);
                $stmUpdate->execute();

                if($stmUpdate === false){
                    sendErrorResponse('400', 'Error updating table ' . $tablename);
                }

                // $sqlInsert = "INSERT INTO $tablename (idClub)
                // VALUES (:idClub)";
                // $stmInsert = $pdo->prepare($sqlInsert);
                // $stmInsert->bindParam(':idClub', $idClub, PDO::PARAM_INT);
                // $stmInsert->execute();
            } else {
                sendErrorResponse('400', 'User doesn t exist!');
            }
        }  
    
        sendSuccessResponse('200', 'Updated Successfuly!', '');
    } catch (PDOException $e) {
        sendErrorResponse('400', $e->getMessage());
    }
} else if($route == 'answerRequestTeam'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        echo $requestData;
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    // check if the coach already made a request and the admin answered

    // $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idRequestTeam = isset($requestData['idRequestTeam']) ? $requestData['idRequestTeam'] : '';
    $idRequester = isset($requestData['idRequester']) ? $requestData['idRequester'] : '';
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $state = isset($requestData['state']) ? $requestData['state'] : '';
    $pending = 'pending'; // only update table if request its pending
    
    if($idRequestTeam === ''){
        sendErrorResponse('400', 'Id Request Club it s empty!');
    }
    if($idRequester === ''){
        sendErrorResponse('400', 'Id Requester it s empty!');
    }
    if($idTeam === ''){
        sendErrorResponse('400', 'Id Club it s empty!');
    }

    try {    
        // echo 'pass';
        $sql = "UPDATE requeststoteam SET statee = :statee WHERE (idRequestTeam = :idRequestTeam && statee = :pending)";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idRequestTeam', $idRequestTeam, PDO::PARAM_INT);
        $stm->bindParam(':statee', $state, PDO::PARAM_STR);
        $stm->bindParam(':pending', $pending, PDO::PARAM_STR); // this is to check if request its pending
        $stm->execute();

        $rowCount = $stm->rowCount(); // Get the number of affected rows

        if ($rowCount === false) {
            // There was an error retrieving the row count
            sendErrorResponse('400', 'Error checking the number of affected rows in requeststoteam!');
        } elseif ($rowCount === 0) {
            // No rows were updated, which means the condition didn't match any records
            sendErrorResponse('400', 'No matching records found for the update condition in requeststoteam!');
        }


        // only update tablename where the answer its accepted
        if($state == 'accepted'){
            $sqlSelect = "SELECT * FROM Users WHERE idUser = :idUser";
            $stmSelect = $pdo->prepare($sqlSelect);
            $stmSelect->bindParam(':idUser', $idRequester, PDO::PARAM_INT);
            $stmSelect->execute();
        
            $data = $stmSelect->fetch(PDO::FETCH_ASSOC);
            // echo $data['typeUser'];


            
            if($data != 0){
                if($data['typeUser'] == 'Coach'){
                    $tablename = 'coaches';
                    $id = 'idCoach';
                } else if($data['typeUser'] == 'Player'){
                    $tablename = 'players';
                    $id = 'idPlayer';
                }

                // get the id of the table tablename
                $sqlSelect1 = "SELECT * FROM $tablename WHERE idUser = :idUser";
                $stmSelect1 = $pdo->prepare($sqlSelect1);
                $stmSelect1->bindParam(':idUser', $idRequester, PDO::PARAM_INT);
                $stmSelect1->execute();
            
                $data1 = $stmSelect1->fetch(PDO::FETCH_ASSOC);

                if($data1 != 0){
                    if($data['typeUser'] == 'Coach'){
                        $value = $data1['idCoach'];

                        $sqlUpdate = "UPDATE Teams SET idCoach = :idCoach WHERE idTeam = :idTeam";
                        $stmUpdate = $pdo->prepare($sqlUpdate);
                        $stmUpdate->bindParam(':idCoach', $value, PDO::PARAM_INT);
                        $stmUpdate->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
                        $stmUpdate->execute();

                        if($stmUpdate === false){
                            sendErrorResponse('400', 'Error updating table ' . $tablename);
                        }
                    } else if($data['typeUser'] == 'Player'){
                        $value = $data1['idPlayer'];

                        $sqlUpdate = "UPDATE Players SET idTeam = :idTeam WHERE $id = :id";
                        $stmUpdate = $pdo->prepare($sqlUpdate);
                        $stmUpdate->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
                        $stmUpdate->bindParam(':id', $value, PDO::PARAM_STR);
                        $stmUpdate->execute();

                        if($stmUpdate === false){
                            sendErrorResponse('400', 'Error updating table ' . $tablename);
                        }
                        // echo $value;
                    }
                }
            } else {
                sendErrorResponse('400', 'User doesn t exist!');
            }
        } 

        
    
        sendSuccessResponse('200', 'Updated Successfuly!', '');
    } catch (PDOException $e) {
        sendErrorResponse('400', $e->getMessage());
    }
}