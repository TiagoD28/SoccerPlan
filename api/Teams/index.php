<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

function teamExists($name, $pdo){
    $sql = "SELECT nome FROM Teams WHERE nome = :nome";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':nome', $name, PDO::PARAM_STR);
    $stm->execute();

    $count = $stm->fetchColumn();

    return $count > 0;
}

if($route == 'createTeamClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }    
    
    $name = isset($requestData['nameTeam']) ? $requestData['nameTeam'] : '';
    $age = isset($requestData['age']) ? $requestData['age'] : '';
    $fieldOf = isset($requestData['fieldOf']) ? $requestData['fieldOf'] : '';
    $rank = isset($requestData['rank']) ? $requestData['rank'] : '';
    $ab = isset($requestData['ab']) ? $requestData['ab'] : '';
    $img = isset($requestData['img']) ? base64_encode($requestData['img']) : '';
    $idCoach = $requestData['idCoach'] != '' ? $requestData['idCoach'] : NULL;
    $idChampionship = $requestData['idChampionship'] != '' ? $requestData['idChampionship'] : NULL;
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    
    try {
        $sql = "INSERT INTO Teams (nameTeam, age, img, idCoach, idChampionship, rank, ab, idClub, fieldOf)
                VALUES (:nameTeam, :age, :img, :idCoach, :idChampionship, :rank, :ab, :idClub, :fieldOf)";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':nameTeam', $name, PDO::PARAM_STR);
        $stm->bindParam(':age', $age, PDO::PARAM_INT);
        $stm->bindParam(':img', $img, PDO::PARAM_LOB);
        $stm->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
        $stm->bindParam(':idChampionship', $idChampionship, PDO::PARAM_INT);
        $stm->bindParam(':rank', $rank, PDO::PARAM_STR);
        $stm->bindParam(':ab', $ab, PDO::PARAM_STR);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->bindParam(':fieldOf', $fieldOf, PDO::PARAM_INT);
        $stm->execute();
    
        // Check if there was any error during the execution
        if ($stm->errorCode() !== '00000') {
            $errorInfo = $stm->errorInfo();
            sendErrorResponse('400', 'Error inserting: ' . $errorInfo[2]);
        }
    
        sendSuccessResponse('200', 'Successful registration!', '');
    
    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

} else if($route === 'updateTeam'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    
    $name = isset($requestData['nameTeam']) ? $requestData['nameTeam'] : '';
    $age = isset($requestData['age']) ? $requestData['age'] : '';
    $fieldOf = isset($requestData['fieldOf']) ? $requestData['fieldOf'] : '';
    $rank = isset($requestData['rank']) ? $requestData['rank'] : '';
    $ab = isset($requestData['ab']) ? $requestData['ab'] : '';
    $img = isset($requestData['img']) ? $requestData['img'] : '';
    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';
    $idChampionship = isset($requestData['idChampionship']) ? $requestData['idChampionship'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    
    if($idCoach == ''){
        $idCoach = NULL;
    }
    if($idChampionship == ''){
        $idChampionship = NULL;
    }

    try {
        $sql = "UPDATE Teams SET nameTeam = :nameTeam,
                    age = :age,
                    img = :img,
                    idCoach = :idCoach,
                    idChampionship = :idChampionship,
                    rank = :rank,
                    ab = :ab,
                    idClub = :idClub,
                    fieldOf = :fieldOf
                WHERE idTeam = :idTeam";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':nameTeam', $name, PDO::PARAM_STR);
        $stm->bindParam(':age', $age, PDO::PARAM_STR);
        $stm->bindParam(':img', $img, PDO::PARAM_LOB);
        $stm->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
        $stm->bindParam(':idChampionship', $idChampionship, PDO::PARAM_INT);
        $stm->bindParam(':rank', $rank, PDO::PARAM_STR);
        $stm->bindParam(':ab', $ab, PDO::PARAM_STR);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->bindParam(':fieldOf', $fieldOf, PDO::PARAM_INT);
        $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);

        $stm->execute();
    
        sendSuccessResponse('200', 'Team updated successfully');
    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

} else if($route === 'getTeams'){
    $sql = "SELECT * FROM Teams";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as &$row) {
        $row['img'] = base64_decode($row['img']);
    }

    sendSuccessResponse('200', 'Success', $data);

} else if($route == 'getAvatars'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }

    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';

    if ($idTeam == '') {
        sendErrorResponse('400', 'Must have team introduced!');
    }

    $sql = "SELECT P.idUser, P.idPlayer, P.img, U.username, U.firstName, U.lastName
            FROM Players P
            INNER JOIN Users U ON P.idUser = U.idUser
            WHERE P.idTeam = :idTeam";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idTeam', $idTeam);
    $stm->execute();
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    
    if (empty($data)) {
        sendErrorResponse('200', 'No players found for the specified team', $data);
    } else {
        foreach ($data as &$row) {
            if ($row['img'] != null) {
                $row['img'] = base64_decode($row['img']);
            }
        }
        
        sendSuccessResponse('200', 'Success', $data);
    }

} else if($route === 'deleteTeam'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }

    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';

    if ($idTeam == '') {
        sendErrorResponse('400', 'Must have team introduced!');
    }

    try {
        // Delete the team
        $sqlTeam = "DELETE FROM Teams WHERE idTeam = :idTeam";
        $sqlTeam = $pdo->prepare($sqlTeam);
        $sqlTeam->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $sqlTeam->execute();
        
        sendSuccessResponse('200', 'Team deleted successfully!');
    } catch (Exception $e) {
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }


} else if($route === 'getTeamsClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';

    if ($idClub == '') {
        sendErrorResponse('400', 'Must have club introduced!');
    }

    $sql = "SELECT * FROM Teams WHERE idClub = :idClub";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    $stm->execute();
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as &$row) {
        $row['img'] = base64_encode($row['img']);
    }

    sendSuccessResponse('200', 'Success', $data);

} else if($route === 'getTeamsPoints'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';

    if ($idClub == '') {
        sendErrorResponse('400', 'Must have club introduced!');
    }

    $sql = "SELECT idTeam, nameTeam, age, idCoach, rank, idStatisticT FROM Teams WHERE idClub = :idClub";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    $stm->execute();
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as &$team) {
        // Get idStatisticT for each team
        $idStatisticT = $team['idStatisticT'];

        // Retrieve statistics data for the current team
        $sqlStatistics = "SELECT * FROM StatisticsT WHERE idStatisticT = :idStatisticT";
        $stmStatistics = $pdo->prepare($sqlStatistics);
        $stmStatistics->bindParam(':idStatisticT', $idStatisticT, PDO::PARAM_INT);
        $stmStatistics->execute();
        $statisticsData = $stmStatistics->fetch(PDO::FETCH_ASSOC);

        // Merge team information with statistics
        $team = array_merge($team, $statisticsData);
    }

    sendSuccessResponse('200', 'Success', $data);


} else if($route === 'getStatistics'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }

    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';

    if ($idTeam == '') {
        sendErrorResponse('400', 'Must have team!');
    }

    $sql = "SELECT nameTeam, age, idCoach, rank, idStatisticT FROM Teams WHERE idTeam = :idTeam";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
    $stm->execute();
    $data = $stm->fetch(PDO::FETCH_ASSOC);

    $sqlStats = "SELECT * FROM StatisticsT WHERE idStatisticT = :idStatisticT";
    $stmStats = $pdo->prepare($sqlStats);
    $stmStats->bindParam(':idStatisticT', $data['idStatisticT']);
    $stmStats->execute();
    $stats = $stmStats->fetch(PDO::FETCH_ASSOC);
    
    sendSuccessResponse('200', 'Success', $stats);


} else if($route == 'getTeamData'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }
    
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    
    if ($idTeam == '') {
        sendErrorResponse('400', 'Must have team!');
    }
    
    try{
        $sql = "SELECT * FROM Teams WHERE idTeam = :idTeam";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stm->execute();
        $data = $stm->fetch(PDO::FETCH_ASSOC);

        if(!empty($data['img'])){
            $data['img'] = base64_decode($data['img']);
        }
        
        if(!$data){
            sendErrorResponse('400', 'Doesn t have data!');
        }

        sendSuccessResponse('200', 'Success', $data);
    }catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

}