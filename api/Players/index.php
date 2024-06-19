<?php
// Load your database connection and data.php
require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

function findUserById($users, $userId) {
    foreach ($users as $user) {
        if ($user['idUser'] == $userId) {
            return $user;
        }
    }
    return array(); // Return an empty array if user is not found
}

if($route === 'getPlayers'){
    $sqlPlayers = "SELECT * FROM Players";
    $stmPlayers = $pdo->prepare($sqlPlayers);
    $stmPlayers->execute();

    $dataPlayers = $stmPlayers->fetchAll(PDO::FETCH_ASSOC);
    
    $mergedData = array();
    foreach ($dataPlayers as $player) {
        $sqlUsers = "SELECT * FROM Users WHERE idUser = :idUser";
        $stmUsers = $pdo->prepare($sqlUsers);
        $stmUsers->bindParam(':idUser', $player['idUser'], PDO::PARAM_INT);
        $stmUsers->execute();

        if($player['img'] != null){
            $player['img'] = base64_decode($player['img']);
        }
    
        $dataUser = $stmUsers->fetch(PDO::FETCH_ASSOC);

        $mergedData[] = array_merge($dataUser, $player);
    }
    
    sendSuccessResponse('200', 'Success getting all players!', $mergedData);

} else if($route === 'getPlayersClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';

    if ($idClub == '') {
        sendErrorResponse('400', 'Must have club introduced!');
    }

    $sql = "SELECT * FROM Players WHERE idClub = :idClub";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    $stm->execute();
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($data as $player) {
        if(!empty($player['img'])){
            $player['img'] = base64_decode($player['img']);
        }

        $sqlUser = "SELECT * FROM Users WHERE idUser = :idUser";
        $stmUser = $pdo->prepare($sqlUser);
        $stmUser->bindParam(':idUser', $player['idUser']);
        $stmUser->execute();

        $dataUser = $stmUser->fetch(PDO::FETCH_ASSOC);
        
        if(!empty($player['idTeam'])){
            $sqlTeam = "SELECT nameTeam FROM Teams WHERE idTeam = :idTeam";
            $stmTeam = $pdo->prepare($sqlTeam);
            $stmTeam->bindParam(':idTeam', $player['idTeam']);
            $stmTeam->execute();
            
            $team = $stmTeam->fetch(PDO::FETCH_ASSOC);
            $allData = array_merge($dataUser, $player, $team);
        } else {
            $allData = array_merge($dataUser, $player);
        }
            
        $allPlayers[] = $allData;
    }

    sendSuccessResponse('200', 'Success', $allPlayers);

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

    try{
        $sql = "SELECT * FROM Players WHERE idTeam = :idTeam";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stm->execute();
        $players = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($players as &$row) {
            if ($row['img'] != null) {
                $row['img'] = base64_decode($row['img']);
            }
        }

        $playerStats = array();

        foreach($players as $player){
            $sqlU = "SELECT * FROM Users WHERE idUser = :idUser";
            $stmU = $pdo->prepare($sqlU);
            $stmU->bindParam(':idUser', $player['idUser']);
            $stmU->execute();
            $userData = $stmU->fetch(PDO::FETCH_ASSOC);

            $sqlS = "SELECT * FROM StatisticsP WHERE idStatisticP = :id";
            $stmS = $pdo->prepare($sqlS);
            $stmS->bindParam(':id', $player['idStatisticP']);
            $stmS->execute();
            $stats = $stmS->fetch(PDO::FETCH_ASSOC);
            
            $playerData = array_merge($userData, $player, $stats);
            
            $playerStats[] = $playerData;
        }

        if($playerStats === null){
            sendSuccessResponse('200', 'Players dont have statistics yet!');
        }

        sendSuccessResponse('200', 'Success', $playerStats);
    }catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

} else if($route === 'leaveTeam'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalid JSON data');
    }

    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : NULL;
    $idPlayer = isset($requestData['idPlayer']) ? $requestData['idPlayer'] : NULL;

    if ($idTeam == NULL) {
        sendErrorResponse('400', 'Must have team!');
    }
    if ($idPlayer == NULL) {
        sendErrorResponse('400', 'Must have idPlayer!');
    }

    try{
        $sql = "UPDATE Players SET idTeam = NULL WHERE idPlayer = :idPlayer";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idPlayer', $idPlayer, PDO::PARAM_INT);
        $stm->execute();

        // $data = $stm->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

    sendSuccessResponse('200', 'Player left the team successfuly!');

} else if($route == 'leaveClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $idPlayer = isset($requestData['idPlayer']) ? $requestData['idPlayer'] : '';

    if($idPlayer == ''){
        sendErrorResponse('400', 'Id player empty');
    }

    try{
        $sql = "UPDATE Players SET idClub = NULL, idTeam = NULL WHERE idPlayer = :idPlayer";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idPlayer', $idPlayer, PDO::PARAM_INT);
        $stm->execute();

        sendSuccessResponse('200', 'Player left the club successfuly!');

    }catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

} else {
    // Handle 404 Not Found
    http_response_code(404);
    echo 'Route not found!';  
}