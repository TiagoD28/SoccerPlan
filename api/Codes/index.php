<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');
require_once('../Notifications/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

if($route == 'sendCodeClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $code = isset($requestData['code']) ? $requestData['code'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idPlayer = isset($requestData['idPlayer']) ? $requestData['idPlayer'] : '';
    $idEmployer = isset($requestData['idEmployer']) ? $requestData['idEmployer'] : '';
    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    $status = 'accepted';
    $state = 'pending';
    $used = 0;

    if($idClub === ''){
        sendErrorResponse('400', 'You must select Club to join!');
    }
    if($code === ''){
        sendErrorResponse('400', 'You must insert the Code!');
    }
    if($idUser === ''){
        sendErrorResponse('400', 'You must insert the idUser!');
    }

    try{
        // check if code exists
        $sql = "SELECT * FROM ClubsCodes WHERE (randomCode = :code AND used = :used) AND (idClub = :idClub AND idReceiver = :idUser)";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':code', $code, PDO::PARAM_STR);
        $stm->bindParam(':used', $used, PDO::PARAM_INT);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stm->execute();
    
        $data = $stm->fetch(PDO::FETCH_ASSOC);
        
        if($data == false){
            sendErrorResponse('400', 'Invalid code or code already used or club doesn\'t have this code!');
        }
    
        // update de value of used(used - 0 = (false-> code never used) - 1 (true-> code already used))
        $updateValue = 1;
        $sqlUpdate = "UPDATE ClubsCodes SET used = :updateValue WHERE randomCode = :code";
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
                sendErrorResponse('500', 'Error updating the value of table Players: ' . $errorInfo[2]);
            }
        }

        if($idEmployer != ''){
            // update the idCoach of the team
            $sqlUpdate3 = "UPDATE Employers SET idClub = :idClub WHERE idEmployer = :idEmployer";
            $stmUpdate3 = $pdo ->prepare($sqlUpdate3);
            $stmUpdate3->bindParam(':idClub', $idClub, PDO::PARAM_INT);
            $stmUpdate3->bindParam(':idEmployer', $idEmployer, PDO::PARAM_INT);
            $stmUpdate3->execute();
    
            if ($stmUpdate3 === false) {
                // An error occurred
                $errorInfo = $stmUpdate3->errorInfo();
                echo $errorInfo;
                sendErrorResponse('500', 'Error updating the value of column idClub table Employers : ' . $errorInfo[2]);
            }
        }

        $sqlDelete = "DELETE FROM RequestsToClub WHERE idRequester = :idRequester AND statee = :statee";
        $stmDelete = $pdo->prepare($sqlDelete);
        $stmDelete->bindParam(':idRequester', $idUser, PDO::PARAM_INT);
        $stmDelete->bindParam(':statee', $state, PDO::PARAM_STR);
        $stmDelete->execute();
        
        // create notification
        createNotification($pdo, 'joinClubAccepted', $idUser, $idClub, null);
        createNotification($pdo, 'userJoinedClub', $idUser, $idClub, null);
        
        sendSuccessResponse('200', 'Inserted code valid! Welcome to the Club!');

    }catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }


} else if($route == 'sendCodeTeam'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $code = isset($requestData['code']) ? $requestData['code'] : '';
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idPlayer = isset($requestData['idPlayer']) ? $requestData['idPlayer'] : '';
    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    $used = 0;
    $state = 'pending';

    if($idTeam === ''){
        sendErrorResponse('400', 'You must select Team to join!');
    }
    if($code === ''){
        sendErrorResponse('400', 'You must insert the Code!');
    }
    if($idUser === ''){
        sendErrorResponse('400', 'You must insert the idUser!');
    }
    if($idCoach === ''){
        if($idPlayer === ''){
            sendErrorResponse('400', 'Must have id!');
        }
    }
    
    // check if code exists
    $sql = "SELECT * FROM TeamsCodes WHERE (randomCode = :code AND used = :used) AND (idTeam = :idTeam)";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':code', $code, PDO::PARAM_STR);
    $stm->bindParam(':used', $used, PDO::PARAM_INT);
    $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
    $stm->execute();
    
    $data = $stm->fetch(PDO::FETCH_ASSOC);
    
    if($data == false){
        sendErrorResponse('400', 'Invalid code or code already used or team doesn\'t have this code!');
    }
    
    // update de value of used(used - 0 = (false-> code never used) - 1 (true-> code already used))
    $updateValue = 1;
    $sqlUpdate = "UPDATE TeamsCodes SET used = :updateValue WHERE randomCode = :code AND idReceiver = :idUser";
    $stmUpdate = $pdo->prepare($sqlUpdate);
    $stmUpdate->bindParam(':updateValue', $updateValue, PDO::PARAM_INT);
    $stmUpdate->bindParam(':code', $code, PDO::PARAM_STR);
    $stmUpdate->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stmUpdate->execute();

    if ($stmUpdate === false) {
        // An error occurred
        $errorInfo = $stmUpdate->errorInfo();
        sendErrorResponse('500', 'Error updating the value of column used table clubscodes: ' . $errorInfo[2]);
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
            sendErrorResponse('500', 'Error updating the value of column Players table Teams: ' . $errorInfo[2]);
        }
    }

    $sqlDelete = "DELETE FROM RequestsToTeam WHERE idRequester = :idRequester AND statee = :statee";
    $stmDelete = $pdo->prepare($sqlDelete);
    $stmDelete->bindParam(':idRequester', $idUser, PDO::PARAM_INT);
    $stmDelete->bindParam(':statee', $state, PDO::PARAM_STR);
    $stmDelete->execute();

    // create notification
    createNotification($pdo, 'joinTeamAccepted', $idUser, $idClub, $idTeam);
    createNotification($pdo, 'userJoinedTeam', $idUser, $idClub, $idTeam);

    sendSuccessResponse('200', 'Inserted code valid! Welcome to the Team!', $data);


} else if($route == 'generateCode'){ // this is working for web (club/team)

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $idGenerator = isset($requestData['idGenerator']) ? $requestData['idGenerator'] : '';
    $idReceiver = isset($requestData['idReceiver']) ? $requestData['idReceiver'] : '';
    $clubCode = isset($requestData['clubCode']) ? $requestData['clubCode'] : '';
    $teamCode = isset($requestData['teamCode']) ? $requestData['teamCode'] : '';
    $used = 0;

    //check if any variables are empty
    if($idClub === ''){
        sendErrorResponse('400', 'Select club!');
    }
    if($idTeam === ''){
        sendErrorResponse('400', 'Select id team!');
    }
    if($idGenerator === ''){
        sendErrorResponse('400', 'Select generator of the code!');
    }
    if($idReceiver === ''){
        sendErrorResponse('400', 'Select user to receive the code!');
    }
    if($teamCode === ''){
        sendErrorResponse('400', 'You must generate a team code!');
    }

    try{
        if(!empty($clubCode)){
            $sql = "INSERT INTO ClubsCodes (randomCode, used, idClub, idGenerator, idReceiver)
            VALUES (:randomCode, :used, :idClub, :idGenerator, :idReceiver)";
            $stm = $pdo ->prepare($sql);
            $stm->bindParam(':randomCode', $clubCode, PDO::PARAM_STR);
            $stm->bindParam(':used', $used, PDO::PARAM_INT);
            $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
            $stm->bindParam(':idGenerator', $idGenerator, PDO::PARAM_INT);
            $stm->bindParam(':idReceiver', $idReceiver, PDO::PARAM_INT);
            $stm->execute();
        }
    
        // insert code in teams
        $sql = "INSERT INTO TeamsCodes (randomCode, used, idTeam, idGenerator, idReceiver)
        VALUES (:randomCode, :used, :idTeam, :idGenerator, :idReceiver)";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':randomCode', $teamCode, PDO::PARAM_STR);
        $stm->bindParam(':used', $used, PDO::PARAM_INT);
        $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stm->bindParam(':idGenerator', $idGenerator, PDO::PARAM_INT);
        $stm->bindParam(':idReceiver', $idReceiver, PDO::PARAM_INT);
        $stm->execute();

        sendSuccessResponse('200', 'Success generating code!');

    } catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

} else if($route == 'generateCodeClub'){ 

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $idGenerator = isset($requestData['idGenerator']) ? $requestData['idGenerator'] : '';
    $idReceiver = isset($requestData['idReceiver']) ? $requestData['idReceiver'] : '';
    $clubCode = isset($requestData['clubCode']) ? $requestData['clubCode'] : '';
    $used = 0;

    //check if any variables are empty
    if($idClub === ''){
        sendErrorResponse('400', 'Select club!');
    }
    if($idGenerator === ''){
        sendErrorResponse('400', 'Select generator of the code!');
    }
    if($idReceiver === ''){
        sendErrorResponse('400', 'Select user to receive the code!');
    }
    if($clubCode === ''){
        sendErrorResponse('400', 'You must generate a club code!');
    }

    try{
        // if(!empty($clubCode)){
        $sql = "INSERT INTO ClubsCodes (randomCode, used, idClub, idGenerator, idReceiver)
        VALUES (:randomCode, :used, :idClub, :idGenerator, :idReceiver)";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':randomCode', $clubCode, PDO::PARAM_STR);
        $stm->bindParam(':used', $used, PDO::PARAM_INT);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->bindParam(':idGenerator', $idGenerator, PDO::PARAM_INT);
        $stm->bindParam(':idReceiver', $idReceiver, PDO::PARAM_INT);
        $stm->execute();
        // }
        
        // createNotification($pdo, 'codeClubGenerated', $idGenerator, $idClub, null, $clubCode);
        // createNotification($pdo, 'codeClub', $idReceiver, $idClub, null, $clubCode);

        sendSuccessResponse('200', 'Success generating code!');

    } catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

} else if($route === 'getCodesClub'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $allData = [];

    if($idClub === ''){
        sendErrorResponse('400', 'Select club!');
    }

    try{
        $sql = "SELECT * FROM ClubsCodes WHERE idClub = :idClub";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->execute();

        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        if($data == false){
            sendErrorResponse('400', 'Club doesn\'t have codes!');
        }

        foreach($data as $code){
            $sqlClub = "SELECT nameClub FROM Clubs WHERE idClub = :idClub";
            $stmClub = $pdo->prepare($sqlClub);
            $stmClub->bindParam(':idClub', $idClub, PDO::PARAM_INT);
            $stmClub->execute();

            $club = $stmClub->fetch(PDO::FETCH_ASSOC);

            $sqlGenerator = "SELECT idUser, firstName, lastName FROM Users WHERE idUser = :idGenerator";
            $stmGenerator = $pdo->prepare($sqlGenerator);
            $stmGenerator->bindParam(':idGenerator', $code['idGenerator'], PDO::PARAM_INT);
            $stmGenerator->execute();

            $generator = $stmGenerator->fetch(PDO::FETCH_ASSOC);

            $sqlReceiver = "SELECT idUser, firstName, lastName FROM Users WHERE idUser = :idReceiver";
            $stmReceiver = $pdo->prepare($sqlReceiver);
            $stmReceiver->bindParam(':idReceiver', $code['idReceiver'], PDO::PARAM_INT);
            $stmReceiver->execute();

            $receiver = $stmReceiver->fetch(PDO::FETCH_ASSOC);

            $allData[] = array_merge($code, $club, 
            ['firstNameGenerator' => $generator['firstName'], 'lastNameGenerator' => $generator['lastName']],
            $receiver);
        }

        sendSuccessResponse('200', 'Success', $allData);

    } catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }


} else if ($route === 'getCodesTeam') {

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }
    
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $allData = [];

    if ($idClub === '') {
        sendErrorResponse('400', 'Select club!');
    }

    // Get all codes for the specified club
    $sql = "SELECT tc.* FROM TeamsCodes tc
            JOIN Teams t ON tc.idTeam = t.idTeam
            WHERE t.idClub = :idClub";
    
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    $stm->execute();

    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    if ($data == false) {
        sendErrorResponse('400', 'Club doesn\'t have codes!');
    }
    
    foreach($data as $code){
        $sqlTeam = "SELECT nameTeam FROM Teams WHERE idTeam = :idTeam";
        $stmTeam = $pdo->prepare($sqlTeam);
        $stmTeam->bindParam(':idTeam', $code['idTeam'], PDO::PARAM_INT);
        $stmTeam->execute();

        $team = $stmTeam->fetch(PDO::FETCH_ASSOC);

        $sqlGenerator = "SELECT idUser, firstName, lastName FROM Users WHERE idUser = :idGenerator";
        $stmGenerator = $pdo->prepare($sqlGenerator);
        $stmGenerator->bindParam(':idGenerator', $code['idGenerator'], PDO::PARAM_INT);
        $stmGenerator->execute();

        $generator = $stmGenerator->fetch(PDO::FETCH_ASSOC);

        $sqlReceiver = "SELECT idUser, firstName, lastName FROM Users WHERE idUser = :idReceiver";
        $stmReceiver = $pdo->prepare($sqlReceiver);
        $stmReceiver->bindParam(':idReceiver', $code['idReceiver'], PDO::PARAM_INT);
        $stmReceiver->execute();

        $receiver = $stmReceiver->fetch(PDO::FETCH_ASSOC);

        $allData[] = array_merge($code, $team, 
        ['firstNameGenerator' => $generator['firstName'], 'lastNameGenerator' => $generator['lastName']],
        $receiver);
    }

    sendSuccessResponse('200', 'Success', $allData);   

} else if($route === 'deleteCode'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }
    
    $idCode = isset($requestData['idCode']) ? $requestData['idCode'] : '';
    $codeClub = isset($requestData['club']) ? $requestData['club'] : '';

    // Check if any variables are empty
    if ($idCode === '') {
        sendErrorResponse('400', 'Select Code!');
    }

    if($codeClub){ // delete code of the club
        try {
            // Delete the team
            $sql = "DELETE FROM ClubsCodes WHERE idCode = :idCode";
            $sql = $pdo->prepare($sql);
            $sql->bindParam(':idCode', $idCode, PDO::PARAM_INT);
            $sql->execute();
            
            sendSuccessResponse('200', 'Code of the club deleted successfully!');
        } catch (Exception $e) {
            $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
            sendErrorResponse('400', $errorMessage);
        }
    } else { // delete code of the team
        try {
            $sql = "DELETE FROM TeamsCodes WHERE idCode = :idCode";
            $sql = $pdo->prepare($sql);
            $sql->bindParam(':idCode', $idCode, PDO::PARAM_INT);
            $sql->execute();
            
            sendSuccessResponse('200', 'Code of the team deleted successfully!');
        } catch (Exception $e) {
            $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
            sendErrorResponse('400', $errorMessage);
        }
    }
}