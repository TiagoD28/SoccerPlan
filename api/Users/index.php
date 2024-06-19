<?php
// Load your database connection and data.php
require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

if($route == 'getUsers'){
    $sql = "SELECT email, pass, username FROM Users";
    $stm = $pdo->prepare($sql);
    $result = $stm->execute();
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);
    sendSuccessResponse('200', 'Sucess', $data);


} else if($route == 'getInfoUpdatedUser'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $tablename = '';
    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';

    if($idUser == ''){
        sendErrorResponse('400-3', 'Id User must be defined!');
    }

    $sql = "SELECT * FROM Users WHERE idUser = :idUser";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stm->execute();
    $data = $stm->fetch(PDO::FETCH_ASSOC);

    if($data == 0){
        sendErrorResponse('400', 'Error getting the info of the User!');
    }

    if($data['typeUser'] == 'Coach'){
        $tablename = 'Coaches';
    } else if($data['typeUser'] == 'Player') {
        $tablename = 'Players';
    } 

    $sql1 = "SELECT * FROM $tablename WHERE idUser = :idUser";
    $stm1 = $pdo->prepare($sql1);
    $stm1->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stm1->execute();
    $data1 = $stm1->fetch(PDO::FETCH_ASSOC);

    if($data1 == 0){
        sendErrorResponse('400', 'Error getting the info of table ' + $tablename);
    }

    if($data1['img'] != null){
        $data1['img'] = base64_decode($data1['img']);
    }

    $finaldata = array_merge($data, $data1);


    if($data['typeUser'] == 'Coach'){
        $sql2 = "SELECT idTeam FROM Teams WHERE idCoach = :idCoach";
        $stm2 = $pdo->prepare($sql2);
        $stm2->bindParam(':idCoach', $data1['idCoach'], PDO::PARAM_INT);
        $stm2->execute();
        $data2 = $stm2->fetch(PDO::FETCH_ASSOC);

        if($data2 != 0){
            $finaldata['idTeam'] = $data2['idTeam'];
        } else {
            $finaldata['idTeam'] = '';
        }
    }

    sendSuccessResponse('200', 'Sucess', $finaldata);


} else if($route == 'getCoachesPlayers'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $Player = 'Player';
    $Coach = 'Coach';
    $usersInformation = [];

    if($idTeam == ''){
        sendErrorResponse('400', 'Misses Id Team!');
    }

    $sql = "SELECT * FROM Users WHERE typeUser = :player OR typeUser = :coach";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':player', $Player);
    $stm->bindParam(':coach', $Coach);
    $stm->execute();
    $usersData = $stm->fetchAll(PDO::FETCH_ASSOC);

    if($usersData == 0){
        sendErrorResponse('400', 'Doesn t exist users of type Player or Coach!');
    }

    // get the all the info of each user and check if the user its already in the team
    foreach ($usersData as $user) {
        if($user['typeUser'] == 'Coach'){
            $sql1 = "SELECT idCoach, idClub FROM Coaches WHERE idUser = :idUser";
            $stm1 = $pdo->prepare($sql1);
            $stm1->bindParam(':idUser', $user['idUser']);
            $stm1->execute();
            $coachData = $stm1->fetch(PDO::FETCH_ASSOC);
            
            if($coachData){
                $sql2 = "SELECT idTeam FROM Teams WHERE idCoach = :idCoach";
                $stm2 = $pdo->prepare($sql2);
                $stm2->bindParam(':idCoach', $coachData['idCoach']);
                $stm2->execute();
                $teamData = $stm2->fetch(PDO::FETCH_ASSOC);

                // Check if the coach is associated with a team
                if(!empty($coachData['idClub'])){
                    $sqlClub = "SELECT nameClub FROM Clubs WHERE idClub = :idClub";
                    $stmClub = $pdo->prepare($sqlClub);
                    $stmClub->bindParam(':idClub', $coachData['idClub']);
                    $stmClub->execute();
                    $club = $stmClub->fetch(PDO::FETCH_ASSOC);

                    $combinedArray = array_merge($user, $coachData, $club, array('idTeam' => $teamData['idTeam']));
                } else {
                    $combinedArray = array_merge($user, $coachData, array('nameClub' => NULL), array('idTeam' => $teamData['idTeam']));
                }

                // Add the combined array to the result
                $usersInformation[] = $combinedArray;
            } 

        } 
        else if($user['typeUser'] == 'Player'){
            $sql1 = "SELECT idPlayer, idClub, idTeam FROM Players WHERE idUser = :idUser";
            $stm1 = $pdo->prepare($sql1);
            $stm1->bindParam(':idUser', $user['idUser']);
            $stm1->execute();
            $playerData = $stm1->fetch(PDO::FETCH_ASSOC);

            if(!empty($playerData['idClub'])){
                $sqlClub = "SELECT nameClub FROM Clubs WHERE idClub = :idClub";
                $stmClub = $pdo->prepare($sqlClub);
                $stmClub->bindParam(':idClub', $playerData['idClub']);
                $stmClub->execute();
                $club = $stmClub->fetch(PDO::FETCH_ASSOC);

                $combinedArray = array_merge($user, $playerData, $club);
            } else {
                $combinedArray = array_merge($user, $playerData, array('nameClub' => NULL));
            }

            $usersInformation[] = $combinedArray;
        }
    }
    
    sendSuccessResponse('200', 'Success', $usersInformation);


} else if($route == 'getUsersClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : NULL;

    if($idClub == NULL){
        sendErrorResponse('400', 'Must have Club!');
    }   

    try{
        $sql = "SELECT COUNT(u.idUser) as userCount
        FROM Users u
        LEFT JOIN Employers e ON u.idUser = e.idUser
        LEFT JOIN Coaches c ON u.idUser = c.idUser
        LEFT JOIN Players p ON u.idUser = p.idUser
        WHERE e.idClub = :idClub
           OR c.idClub = :idClub
           OR p.idClub = :idClub";

        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $result = $stm->execute();
        $data = $stm->fetch(PDO::FETCH_ASSOC);

        $userCount = $data['userCount'];


        sendSuccessResponse('200', 'Sucess', $data);
    } catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);       
    }


} else if($route == 'updateUser'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : NULL;
    $firstName = isset($requestData['firstName']) ? $requestData['firstName'] : NULL;
    $lastName = isset($requestData['lastName']) ? $requestData['lastName'] : NULL;
    $age = isset($requestData['age']) ? $requestData['age'] : NULL;
    $nacionality = isset($requestData['nacionality']) ? $requestData['nacionality'] : NULL;
    $email = isset($requestData['email']) ? $requestData['email'] : NULL;
    $phoneNumber = isset($requestData['phoneNumber']) ? $requestData['phoneNumber'] : NULL;
    $typeUser = isset($requestData['typeUser']) ? $requestData['typeUser'] : NULL;
    $img = isset($requestData['img']) ? $requestData['img'] : NULL; // for web
    $Image = isset($requestData['base64Image']) ? base64_encode($requestData['base64Image']) : NULL; // for mobile

    try {
        $sql = "UPDATE Users SET 
                    email = :email,
                    firstName = :firstName,
                    lastName = :lastName
                WHERE idUser = :idUser";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':email', $email, PDO::PARAM_STR);
        $stm->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $stm->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        
        $stm->execute();
        
        if ($stm->errorCode() !== '00000') {
            $errorInfo = $stm->errorInfo();
            sendErrorResponse('400', 'Error: ' . $errorInfo);
        }

        if($typeUser == 'ClubAdmin'){
            if(!empty($img)){
                $sqlC = "UPDATE ClubAdmins SET 
                        age = :age,
                        phoneNumber = :phoneNumber,
                        img = :img,
                        nacionality = :nacionality
                    WHERE idUser = :idUser";
                $stmC = $pdo->prepare($sqlC);
                $stmC->bindParam(':age', $age, PDO::PARAM_INT);
                $stmC->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
                $stmC->bindParam(':img', $img, is_null($img) ? PDO::PARAM_NULL : PDO::PARAM_LOB);
                $stmC->bindParam(':nacionality', $nacionality, PDO::PARAM_STR);
                $stmC->bindParam(':idUser', $idUser, PDO::PARAM_INT);
                $stmC->execute();

            } else {

                $sqlC = "UPDATE ClubAdmins SET 
                        age = :age,
                        phoneNumber = :phoneNumber,
                        nacionality = :nacionality
                    WHERE idUser = :idUser";
                $stmC = $pdo->prepare($sqlC);
                $stmC->bindParam(':age', $age, PDO::PARAM_INT);
                $stmC->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
                $stmC->bindParam(':nacionality', $nacionality, PDO::PARAM_STR);
                $stmC->bindParam(':idUser', $idUser, PDO::PARAM_INT);
                $stmC->execute();
            }


            $sqlUser = "SELECT * FROM Users WHERE idUser = :idUser";
            $stmUser = $pdo->prepare($sqlUser);
            $stmUser->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmUser->execute();
            $userData = $stmUser->fetch(PDO::FETCH_ASSOC);

            $sqlAdmin = "SELECT * FROM ClubAdmins WHERE idUser = :idUser";
            $stmAdmin = $pdo->prepare($sqlAdmin);
            $stmAdmin->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmAdmin->execute();
            $adminData = $stmAdmin->fetch(PDO::FETCH_ASSOC);

            if($adminData['img'] != null){
                $adminData['img'] = base64_encode($adminData['img']);
            }
            
            // Merge ClubAdmin data with User data
            $updatedUserData = array_merge($userData, $adminData);

            sendSuccessResponse('200', 'User Updated successfully!', $updatedUserData);


        } else if($typeUser == 'Employer') {
            $sqlE = "UPDATE Employers SET 
                    age = :age,
                    nacionality = :nacionality,
                    phoneNumber = :phoneNumber,
                    img = :img
                WHERE idUser = :idUser";
            $stmE = $pdo->prepare($sqlE);
            $stmE->bindParam(':age', $age, PDO::PARAM_INT);
            $stmE->bindParam(':nacionality', $nacionality, PDO::PARAM_STR);
            $stmE->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
            $stmE->bindParam(':img', $img, is_null($img) ? PDO::PARAM_NULL : PDO::PARAM_LOB);
            $stmE->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmE->execute();

            $sqlUser = "SELECT * FROM Users WHERE idUser = :idUser";
            $stmUser = $pdo->prepare($sqlUser);
            $stmUser->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmUser->execute();
            $userData = $stmUser->fetch(PDO::FETCH_ASSOC);

            $sqlEmployer = "SELECT * FROM Employers WHERE idUser = :idUser";
            $stmEmployer = $pdo->prepare($sqlEmployer);
            $stmEmployer->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmEmployer->execute();

            $employerData = $stmEmployer->fetch(PDO::FETCH_ASSOC);

            if($employerData['img'] != null){
                $employerData['img'] = base64_encode($employerData['img']);
            }

            $updatedUserData = array_merge($userData, $employerData);

            sendSuccessResponse('200', 'User Updated successfully!', $updatedUserData);


        } else if($typeUser == 'Coach') {
            if(!empty($Image)){
                $sqlC = "UPDATE Coaches SET 
                        age = :age,
                        nacionality = :nacionality,
                        phoneNumber = :phoneNumber,
                        img = :img
                    WHERE idUser = :idUser";
                $stmC = $pdo->prepare($sqlC);
                $stmC->bindParam(':age', $age, PDO::PARAM_INT);
                $stmC->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
                $stmC->bindParam(':nacionality', $nacionality, PDO::PARAM_STR);
                $stmC->bindParam(':img', $Image, is_null($Image) ? PDO::PARAM_NULL : PDO::PARAM_LOB);
                $stmC->bindParam(':idUser', $idUser, PDO::PARAM_INT);
                $stmC->execute();

            } else {

                $sqlC = "UPDATE Coaches SET 
                        age = :age,
                        nacionality = :nacionality,
                        phoneNumber = :phoneNumber
                    WHERE idUser = :idUser";
                $stmC = $pdo->prepare($sqlC);
                $stmC->bindParam(':age', $age, PDO::PARAM_INT);
                $stmC->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
                $stmC->bindParam(':nacionality', $nacionality, PDO::PARAM_STR);
                $stmC->bindParam(':idUser', $idUser, PDO::PARAM_INT);
                $stmC->execute();
            }

            $sqlUser = "SELECT * FROM Users WHERE idUser = :idUser";
            $stmUser = $pdo->prepare($sqlUser);
            $stmUser->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmUser->execute();
            $userData = $stmUser->fetch(PDO::FETCH_ASSOC);

            $sqlCoach = "SELECT * FROM Coaches WHERE idUser = :idUser";
            $stmCoach = $pdo->prepare($sqlCoach);
            $stmCoach->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmCoach->execute();

            $coachData = $stmCoach->fetch(PDO::FETCH_ASSOC);

            if($coachData['img'] != null){
                $coachData['img'] = base64_decode($coachData['img']);
            }

            $updatedUserData = array_merge($userData, $coachData);

            sendSuccessResponse('200', 'User Updated successfully!', $updatedUserData);
        
        
        } else if($typeUser == 'Player') {
            if(!empty($Image)){
                $sqlP = "UPDATE Players SET 
                        age = :age,
                        nacionality = :nacionality,
                        phoneNumber = :phoneNumber,
                        img = :img
                    WHERE idUser = :idUser";
                $stmP = $pdo->prepare($sqlP);
                $stmP->bindParam(':age', $age, PDO::PARAM_INT);
                $stmP->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
                $stmP->bindParam(':nacionality', $nacionality, PDO::PARAM_STR);
                $stmP->bindParam(':img', $Image, is_null($Image) ? PDO::PARAM_NULL : PDO::PARAM_LOB);
                $stmP->bindParam(':idUser', $idUser, PDO::PARAM_INT);
                $stmP->execute();

            } else {

                $sqlP = "UPDATE Players SET 
                        age = :age,
                        nacionality = :nacionality,
                        phoneNumber = :phoneNumber
                    WHERE idUser = :idUser";
                $stmP = $pdo->prepare($sqlP);
                $stmP->bindParam(':age', $age, PDO::PARAM_INT);
                $stmP->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
                $stmP->bindParam(':nacionality', $nacionality, PDO::PARAM_STR);
                $stmP->bindParam(':idUser', $idUser, PDO::PARAM_INT);
                $stmP->execute();
            }

            $sqlUser = "SELECT * FROM Users WHERE idUser = :idUser";
            $stmUser = $pdo->prepare($sqlUser);
            $stmUser->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmUser->execute();
            $userData = $stmUser->fetch(PDO::FETCH_ASSOC);

            $sqlPlayer = "SELECT * FROM Players WHERE idUser = :idUser";
            $stmPlayer = $pdo->prepare($sqlPlayer);
            $stmPlayer->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmPlayer->execute();

            $playerData = $stmPlayer->fetch(PDO::FETCH_ASSOC);

            if($playerData['img'] != null){
                $playerData['img'] = base64_decode($playerData['img']);
            }

            $updatedUserData = array_merge($userData, $playerData);

            sendSuccessResponse('200', 'User Updated successfully!', $updatedUserData);
        }

    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

} else if($route == 'getEmpCoaPla'){
        $requestData = json_decode(file_get_contents("php://input"), true);
        if ($requestData === null) {
            // Handle JSON decoding error
            sendErrorResponse('400', 'Invalide JSON data');
        }
    
        $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
        $ClubAdmin = 'ClubAdmin';
        $usersInformation = [];
    
        if($idClub == ''){
            sendErrorResponse('400', 'Misses Id Club!');
        }
    
        $sql = "SELECT * FROM Users WHERE typeUser != :ClubAdmin";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':ClubAdmin', $ClubAdmin);
        $stm->execute();
        $usersData = $stm->fetchAll(PDO::FETCH_ASSOC);
    
        if($usersData == 0){
            sendErrorResponse('400', 'Doesn t exist users of type Player or Coach!');
        }
    
        // get the all the info of each user and check if the user its already in the team
        foreach ($usersData as $user) {
            // sendSuccessResponse('200', 'asdf', $user);
            if($user['typeUser'] == 'Coach'){
                $sql1 = "SELECT idCoach, idClub FROM Coaches WHERE idUser = :idUser";
                $stm1 = $pdo->prepare($sql1);
                $stm1->bindParam(':idUser', $user['idUser']);
                $stm1->execute();
                $coachData = $stm1->fetch(PDO::FETCH_ASSOC);
                
                if ($coachData && (!isset($coachData['idClub']) || $coachData['idClub'] != $idClub)) {
                    $combinedArray = array_merge($user, $coachData);
                    
                    // If the coach is associated with a club, fetch and add club information
                    if (!empty($coachData['idClub'])) {
                        $sqlClub = "SELECT nameClub FROM Clubs WHERE idClub = :idClub";
                        $stmClub = $pdo->prepare($sqlClub);
                        $stmClub->bindParam(':idClub', $coachData['idClub']);
                        $stmClub->execute();
                        $club = $stmClub->fetch(PDO::FETCH_ASSOC);
        
                        $combinedArray = array_merge($combinedArray, $club);
                    } else {
                        // If the coach is not associated with any club, add a placeholder for club information
                        $combinedArray['nameClub'] = null;
                    }
    
                    $usersInformation[] = $combinedArray;
                }
                
            } else if($user['typeUser'] == 'Player'){
                $sql1 = "SELECT idPlayer, idClub FROM Players WHERE idUser = :idUser";
                $stm1 = $pdo->prepare($sql1);
                $stm1->bindParam(':idUser', $user['idUser']);
                $stm1->execute();
                $playerData = $stm1->fetch(PDO::FETCH_ASSOC);

                if ($playerData && (!isset($playerData['idClub']) || $playerData['idClub'] != $idClub)) {
                    $combinedArray = array_merge($user, $playerData);
                    
                    // If the player is associated with a club, fetch and add club information
                    if (!empty($playerData['idClub'])) {
                        $sqlClub = "SELECT nameClub FROM Clubs WHERE idClub = :idClub";
                        $stmClub = $pdo->prepare($sqlClub);
                        $stmClub->bindParam(':idClub', $playerData['idClub']);
                        $stmClub->execute();
                        $club = $stmClub->fetch(PDO::FETCH_ASSOC);
        
                        $combinedArray = array_merge($combinedArray, $club);
                    } else {
                        // If the player is not associated with any club, add a placeholder for club information
                        $combinedArray['nameClub'] = null;
                    }
    
                    $usersInformation[] = $combinedArray;
                }
            } else if($user['typeUser'] == 'Employer'){
                $sql1 = "SELECT idEmployer, idClub FROM Employers WHERE idUser = :idUser";
                $stm1 = $pdo->prepare($sql1);
                $stm1->bindParam(':idUser', $user['idUser']);
                $stm1->execute();
                $employerData = $stm1->fetch(PDO::FETCH_ASSOC);
    
                if ($employerData && (!isset($employerData['idClub']) || $employerData['idClub'] != $idClub)) {
                    $combinedArray = array_merge($user, $employerData);
                    
                    // If the employer is associated with a club, fetch and add club information
                    if (!empty($employerData['idClub'])) {
                        $sqlClub = "SELECT nameClub FROM Clubs WHERE idClub = :idClub";
                        $stmClub = $pdo->prepare($sqlClub);
                        $stmClub->bindParam(':idClub', $employerData['idClub']);
                        $stmClub->execute();
                        $club = $stmClub->fetch(PDO::FETCH_ASSOC);
        
                        $combinedArray = array_merge($combinedArray, $club);
                    } else {
                        // If the employer is not associated with any club, add a placeholder for club information
                        $combinedArray['nameClub'] = null;
                    }
    
                    $usersInformation[] = $combinedArray;
                }
            }
        }
        
        sendSuccessResponse('200', 'Success', $usersInformation);
     
} else {
    // Handle 404 Not Found
    http_response_code(404);
    echo 'Route not found!';  
}