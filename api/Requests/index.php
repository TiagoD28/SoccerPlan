<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');
require_once('../Notifications/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

function clubExists($idClub, $pdo){
    $sql = "SELECT * FROM Clubs WHERE idClub = :idClub";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_STR);
    $stm->execute();

    $count = $stm->fetchColumn();

    return $count > 0;
}

if($route == 'getRequestsClub') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $allData = [];

    if($idClub === ''){
        sendErrorResponse('400', 'Id club it s empty!');
    }
    
    $sql = "SELECT * FROM RequestsToClub WHERE idClub = :idClub";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_STR);
    $stm->execute();

    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    if(count($data) == 0){
        sendErrorResponse('400', 'Doesn t exist requests to Club!');

    } else {

        foreach($data as $request){
            $sqlClub = "SELECT nameClub FROM Clubs WHERE idClub = :idClub";
            $stmClub = $pdo->prepare($sqlClub);
            $stmClub->bindParam(':idClub', $idClub, PDO::PARAM_STR);
            $stmClub->execute();

            $club = $stmClub->fetch(PDO::FETCH_ASSOC);

            $sqlUser = "SELECT firstName, lastName FROM Users WHERE idUser = :idUser";
            $stmUser = $pdo->prepare($sqlUser);
            $stmUser->bindParam(':idUser', $request['idRequester'], PDO::PARAM_STR);
            $stmUser->execute();

            $user = $stmUser->fetch(PDO::FETCH_ASSOC);

            $allData[] = array_merge($request, $club, $user);
        }

        sendSuccessResponse('200', 'Success', $allData);   
    }


} else if($route == 'getRequestsTeam') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        echo $requestData;
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $allData = [];

    if($idClub === ''){
        sendErrorResponse('400', 'Id club it s empty!');
    }
    
    $sql = "SELECT * FROM RequestsToTeam WHERE idClub = :idClub";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_STR);
    $stm->execute();

    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    if(count($data) == 0){
        sendErrorResponse('400', 'Doesn t exist requests to Teams   !');

    } else {

        foreach($data as $request){
            $sqlClub = "SELECT nameClub FROM Clubs WHERE idClub = :idClub";
            $stmClub = $pdo->prepare($sqlClub);
            $stmClub->bindParam(':idClub', $idClub, PDO::PARAM_STR);
            $stmClub->execute();

            $club = $stmClub->fetch(PDO::FETCH_ASSOC);
            
            $sqlTeam = "SELECT nameTeam FROM Teams WHERE idTeam = :idTeam";
            $stmTeam = $pdo->prepare($sqlTeam);
            $stmTeam->bindParam(':idTeam', $request['idTeam'], PDO::PARAM_STR);
            $stmTeam->execute();

            $team = $stmTeam->fetch(PDO::FETCH_ASSOC);

            $sqlUser = "SELECT firstName, lastName FROM Users WHERE idUser = :idUser";
            $stmUser = $pdo->prepare($sqlUser);
            $stmUser->bindParam(':idUser', $request['idRequester'], PDO::PARAM_STR);
            $stmUser->execute();

            $user = $stmUser->fetch(PDO::FETCH_ASSOC);

            $allData[] = array_merge($request, $club, $team, $user);
        }
        sendSuccessResponse('200', 'Success', $allData);   
    }


} else if($route == 'sendRequestClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    
    $idRequester = isset($requestData['idRequester']) ? $requestData['idRequester'] : null;
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : null;
    $state = 'pending';
    
    
    if($idRequester === ''){
        sendErrorResponse('400', 'Id Requester it s empty!');
    }
    if($idClub === ''){
        sendErrorResponse('400', 'Id club it s empty!');
    }

    
    try{
        // check if the user already made a request
        $sqlCheck = "SELECT * FROM RequestsToClub WHERE idRequester = :idRequester AND idClub = :idClub AND statee = :statee";
        $stmCheck = $pdo->prepare($sqlCheck);
        $stmCheck->bindParam(':idRequester', $idRequester, PDO::PARAM_INT);
        $stmCheck->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stmCheck->bindParam(':statee', $state, PDO::PARAM_STR);
        $stmCheck->execute();
        $result = $stmCheck->fetch(PDO::FETCH_ASSOC);
    
        if($result){
            sendErrorResponse('400', 'Already make a request to this Club!');
        }

        $sql = "INSERT INTO RequestsToClub (statee, idRequester, idClub)
                VALUES (:statee, :idRequester, :idClub)";
        $stmInsert = $pdo->prepare($sql);
        $stmInsert->bindParam(':statee', $state, PDO::PARAM_STR);
        $stmInsert->bindParam(':idRequester', $idRequester, PDO::PARAM_INT);
        $stmInsert->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stmInsert->execute();
        
        // create notification
        createNotification($pdo, 'joinClub', $idRequester, $idClub, null);
    
        sendSuccessResponse('200', 'Request sended to club successfuly!', '');

    }catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

} else if($route == 'sendRequestTeam'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        echo $requestData;
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    
    $idRequester = isset($requestData['idRequester']) ? $requestData['idRequester'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : null;
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $state = 'pending';

    if($idRequester === ''){
        sendErrorResponse('400', 'Id user it s empty!');
    }
    if($idTeam === ''){
        sendErrorResponse('400', 'Must select team it s empty!');
    }
    
    try{
        // check if the user already made a request
        $sqlCheck = "SELECT * FROM RequestsToTeam WHERE idRequester = :idRequester AND idTeam = :idTeam AND statee = :statee";
        $stmCheck = $pdo->prepare($sqlCheck);
        $stmCheck->bindParam(':statee', $state, PDO::PARAM_STR);
        $stmCheck->bindParam(':idRequester', $idRequester, PDO::PARAM_INT);
        $stmCheck->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stmCheck->execute();
        $result = $stmCheck->fetch(PDO::FETCH_ASSOC);

        if($result){
            sendErrorResponse('400', 'Already make a request to this Team!');
        }

        // // create notification to user know that he entered in the club
        $sql = "INSERT INTO RequestsToTeam (statee, idRequester, idClub, idTeam)
        VALUES (:statee, :idRequester, :idClub, :idTeam)";
        $stmInsert = $pdo->prepare($sql);
        $stmInsert->bindParam(':statee', $state, PDO::PARAM_STR);
        $stmInsert->bindParam(':idRequester', $idRequester, PDO::PARAM_INT);
        $stmInsert->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stmInsert->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stmInsert->execute();
    
        if ($stmInsert === false) {
            // An error occurred
            sendErrorResponse('400', 'Error inserting the values!');
        }
        
        // create notification
        createNotification($pdo, 'joinTeam', $idRequester, $idClub, $idTeam);
        
        sendSuccessResponse('200', 'Request sended to team successfuly!', '');

    }catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }


} else if($route == 'answerRequestClub'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        echo $requestData;
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idRequestClub = isset($requestData['idRequestClub']) ? $requestData['idRequestClub'] : '';
    $idRequester = isset($requestData['idRequester']) ? $requestData['idRequester'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $state = isset($requestData['state']) ? $requestData['state'] : '';
    $pending = 'pending'; // only update table if request its pending

    if($idRequester === ''){
        sendErrorResponse('400', 'Id Requester it s empty!');
    }
    if($idClub === ''){
        sendErrorResponse('400', 'Id Club it s empty!');
    }

    try {    

        // $sql = "UPDATE RequestsToClub SET statee = :statee WHERE (idRequestClub = :idRequestClub AND statee = :pending)";
        $sql = "UPDATE RequestsToClub SET statee = :statee WHERE (idClub = :idClub AND statee = :pending)";
        $stm = $pdo->prepare($sql);
        // $stm->bindParam(':idRequestClub', $idRequestClub, PDO::PARAM_INT);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->bindParam(':statee', $state, PDO::PARAM_STR);
        $stm->bindParam(':pending', $pending, PDO::PARAM_STR); // this is to check if request its pending
        $stm->execute();

        $rowCount = $stm->rowCount(); 

        if ($rowCount === false) {
            // There was an error retrieving the row count
            sendErrorResponse('400', 'Error checking the number of affected rows in requeststoclub!');
        } else if ($rowCount === 0) {
            // sendErrorResponse('400', 'No matching records found for the update condition in requeststoclub!');
            sendErrorResponse('400', 'Request already answered!');
        }


        // only update tablename where the answer its accepted
        if($state == 'accepted'){
            $sqlSelect = "SELECT * FROM Users WHERE idUser = :idUser";
            $stmSelect = $pdo->prepare($sqlSelect);
            $stmSelect->bindParam(':idUser', $idRequester, PDO::PARAM_INT);
            $stmSelect->execute();
        
            $data = $stmSelect->fetch(PDO::FETCH_ASSOC);

            if($data != 0){
                if($data['typeUser'] == 'Employer'){
                    $tablename = 'Employers';
                    $id = 'idEmployer';
                } else if($data['typeUser'] == 'ClubAdmin'){
                    $tablename = 'ClubAdmins';
                    $id = 'idClubAdmin';
                } else if($data['typeUser'] == 'Coach'){
                    $tablename = 'Coaches';
                    $id = 'idCoach';
                } else if($data['typeUser'] == 'Player'){
                    $tablename = 'Players';
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
                    }
                }
                // end 

                $sqlUpdate = "UPDATE $tablename SET idClub = :idClub WHERE $id = :id";
                $stmUpdate = $pdo->prepare($sqlUpdate);
                $stmUpdate->bindParam(':idClub', $idClub, PDO::PARAM_INT);
                $stmUpdate->bindParam(':id', $value, PDO::PARAM_STR);
                $stmUpdate->execute();

                if($stmUpdate === false){
                    sendErrorResponse('400', 'Error updating table ' . $tablename);
                }

            } else {
                sendErrorResponse('400', 'User doesn t exist!');
            }

            // create notification
            createNotification($pdo, 'joinClubAccepted', $idRequester, $idClub, null);
            createNotification($pdo, 'userJoinedClub', $idRequester, $idClub, null);

            sendSuccessResponse('200', 'Request accepted successfuly!');

        } else {
            // create notification
            createNotification($pdo, 'joinClubRejected', $idRequester, $idClub, null);

            sendSuccessResponse('200', 'Request rejected successfuly!');
        }
    
    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }


} else if($route == 'answerRequestTeam'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idRequestTeam = isset($requestData['idRequestTeam']) ? $requestData['idRequestTeam'] : '';
    $idRequester = isset($requestData['idRequester']) ? $requestData['idRequester'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $state = isset($requestData['state']) ? $requestData['state'] : '';
    $pending = 'pending'; // only update table if request its pending
    
    if($idRequester === ''){
        sendErrorResponse('400', 'Id Requester it s empty!');
    }
    if($idTeam === ''){
        sendErrorResponse('400', 'Id Team it s empty!');
    }

    try {
        // get type user
        $sqlCheck = "SELECT * FROM Users WHERE idUser = :idUser";
        $stmCheck = $pdo->prepare($sqlCheck);
        $stmCheck->bindParam(':idUser', $idRequester, PDO::PARAM_INT);
        $stmCheck->execute();
    
        $dataCheck = $stmCheck->fetch(PDO::FETCH_ASSOC);

        // if type user coach i need to check if teams already has coach
        if($dataCheck['typeUser'] === 'Coach' && $state ==='accepted'){
            $sqlCheck = "SELECT * FROM Teams WHERE idTeam = :idTeam AND idCoach IS NOT NULL";
            $stmCheck = $pdo->prepare($sqlCheck);
            $stmCheck->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
            $stmCheck->execute();
            // $dataCheck = $stmCheck->fetch(PDO::FETCH_ASSOC);
            
            if($stmCheck->rowCount() > 0){
                sendErrorResponse('400', 'Team already has a coach!');   
            }
        }

        // $sql = "UPDATE RequestsToTeam SET statee = :statee WHERE idRequestTeam = :idRequestTeam AND statee = :pending";
        $sql = "UPDATE RequestsToTeam SET statee = :statee WHERE idRequester = :idRequester AND idTeam = :idTeam AND statee = :pending";
        // $sql = "UPDATE RequestsToTeam SET statee = :statee WHERE (idRequestTeam = :idRequestTeam AND statee = :pending) AND idCoach IS NULL";
        $stm = $pdo->prepare($sql);
        // $stm->bindParam(':idRequestTeam', $idRequestTeam, PDO::PARAM_INT);
        $stm->bindParam(':idRequester', $idRequester, PDO::PARAM_INT);
        $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stm->bindParam(':statee', $state, PDO::PARAM_STR);
        $stm->bindParam(':pending', $pending, PDO::PARAM_STR); // this is to check if request its pending
        $stm->execute();

        $rowCount = $stm->rowCount();

        if ($rowCount === false) {
            // There was an error retrieving the row count
            sendErrorResponse('400', 'Error checking the number of affected rows in requeststoteam!');
        } elseif ($rowCount === 0) {
            // No rows were updated, which means the condition didn't match any records
            sendErrorResponse('400', 'Request already answered or Team already has a Coach!');
        }

        // only update tablename where the answer its accepted
        if($state == 'accepted'){
            $sqlSelect = "SELECT * FROM Users WHERE idUser = :idUser";
            $stmSelect = $pdo->prepare($sqlSelect);
            $stmSelect->bindParam(':idUser', $idRequester, PDO::PARAM_INT);
            $stmSelect->execute();
        
            $data = $stmSelect->fetch(PDO::FETCH_ASSOC);
            
            if($data != 0){
                if($data['typeUser'] == 'Coach'){
                    $tablename = 'Coaches';
                    $id = 'idCoach';
                } else if($data['typeUser'] == 'Player'){
                    $tablename = 'Players';
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
                    }
                }
            } else {
                sendErrorResponse('400', 'User doesn t exist!');
            }

            // create notification
            createNotification($pdo, 'joinTeamAccepted', $idRequester, $idClub, $idTeam);
            createNotification($pdo, 'userJoinedTeam', $idRequester, $idClub, $idTeam);

            sendSuccessResponse('200', 'Request accepted Successfuly!', '');

        } else {
            // create notification
            createNotification($pdo, 'joinTeamRejected', $idRequester, $idClub, $idTeam);
            sendSuccessResponse('200', 'Request rejected successfuly!');
        }

    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }
}