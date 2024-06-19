<?php
// Load your database connection and data.php
require_once('../Connection/data.php');
require_once('../Response/index.php');
require_once('../Notifications/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

if($route == 'addEvent'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        sendErrorResponse('400', 'Invalid JSON data');
    }

    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : NULL;
    $typeEvent = isset($requestData['typeEvent']) ? $requestData['typeEvent'] : '';
    $startDate = isset($requestData['startDate']) ? $requestData['startDate'] : '';
    $endDate = isset($requestData['endDate']) ? $requestData['endDate'] : '';
    $meetTime = isset($requestData['meetTime']) ? $requestData['meetTime'] : '';
    $locall = isset($requestData['local']) ? $requestData['local'] : '';
    $meetingLocal = isset($requestData['meetingLocal']) ? $requestData['meetingLocal'] : '';
    $stadium = isset($requestData['stadium']) ? $requestData['stadium'] : '';

    if($typeEvent == ''){
        sendErrorResponse('400', 'Type event must be selected!'); 
    }
    if($startDate == ''){
        sendErrorResponse('400', 'Start date must be selected!'); 
    }
    if($endDate == ''){
        sendErrorResponse('400', 'End date must be selected!!');
    }
    if($meetTime == ''){
        sendErrorResponse('400', 'Meeting time must be selected!');
    }
    if($meetingLocal == ''){
        sendErrorResponse('400', 'Meeting local must be defined!');
    }
    if($idClub == ''){
        sendErrorResponse('400', 'Id Club must be defined!');
    }

    try{
        // Insert data into the 'Events' table
        $sql = "INSERT INTO Events (typeEvent, startDate, endDate, meetTime, locall, meetingLocal, idClub, idTeam, idUser)
        VALUES (:typeEvent, :startDate, :endDate, :meetTime, :locall, :meetingLocal, :idClub, :idTeam, :idUser)";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':typeEvent', $typeEvent, PDO::PARAM_STR);
        $stm->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $stm->bindParam(':endDate', $endDate, PDO::PARAM_STR);
        $stm->bindParam(':meetTime', $meetTime, PDO::PARAM_STR);
        $stm->bindParam(':locall', $locall, PDO::PARAM_STR);
        $stm->bindParam(':meetingLocal', $meetingLocal, PDO::PARAM_STR);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        
        $result = $stm->execute();
        if ($result) {
            // create notification
            createNotification($pdo, 'eventAdded', $idUser, $idClub, $idTeam);
            sendSuccessResponse('200', 'Event created successfuly!', '');
        } else {
            sendErrorResponse('500', 'Failed to create event!');
        }
    } catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

} else if($route == 'getEvents') {
    $sql = "SELECT * FROM Events";
    $stm = $pdo->prepare($sql);
    $result = $stm->execute();
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);
    sendSuccessResponse('200', 'Success', $data);

} else if($route == 'getPastEvents') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        sendErrorResponse('400', 'Invalid JSON data');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $currentDateTime = isset($requestData['currentDateTime']) ? $requestData['currentDateTime'] : '';

    if($idClub == ""){
        sendErrorResponse('400', 'You must have a Club!');
    }

    $data = [];

    $sqlClub = "SELECT * FROM Events WHERE idClub = :idClub AND STR_TO_DATE(startDate, '%d-%m-%Y') <= CURDATE()";
    $stmClub = $pdo->prepare($sqlClub);
    $stmClub->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    $result = $stmClub->execute();
    $dataClub = $stmClub->fetchAll(PDO::FETCH_ASSOC);

    // Check if $data has zero rows
    if ($stmClub->rowCount() == 0) {
        sendErrorResponse('400', 'There is no past events of the Club!');
    }

    if($idTeam != ""){
        $sqlTeam = "SELECT * FROM Events WHERE idTeam = :idTeam AND STR_TO_DATE(startDate, '%d-%m-%Y') <= CURDATE()";
        $stmTeam = $pdo->prepare($sqlTeam);
        $stmTeam->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $result = $stmTeam->execute();
        $dataTeam = $stmTeam->fetchAll(PDO::FETCH_ASSOC);

        // Check if $data has zero rows
        if ($stmTeam->rowCount() != 0) {
            $data = array_merge($dataClub, $dataTeam);
            sendSuccessResponse('200', 'Success', $data);
        }
    }
    
    sendSuccessResponse('200', 'Success', $dataClub);

} else if($route == 'getNextEvents') {

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        sendErrorResponse('400', 'Invalid JSON data!');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $currentDateTime = isset($requestData['currentDateTime']) ? $requestData['currentDateTime'] : '';

    if($idClub == ""){
        sendErrorResponse('400', 'You must have a Club!');
    }

    $data = [];

    $sqlClub = "SELECT * FROM Events WHERE idClub = :idClub AND STR_TO_DATE(startDate, '%d-%m-%Y') > CURDATE()";
    $stmClub = $pdo->prepare($sqlClub);
    $stmClub->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    $result = $stmClub->execute();
    $dataClub = $stmClub->fetchAll(PDO::FETCH_ASSOC);

    // Check if $data has zero rows
    if ($stmClub->rowCount() == 0) {
        sendErrorResponse('400', 'There is no next events of the Club!');
    }

    if($idTeam != ""){
        $sqlTeam = "SELECT * FROM Events WHERE idTeam = :idTeam AND STR_TO_DATE(startDate, '%d-%m-%Y') > CURDATE()";
        $stmTeam = $pdo->prepare($sqlTeam);
        $stmTeam->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
        $result = $stmTeam->execute();
        $dataTeam = $stmTeam->fetchAll(PDO::FETCH_ASSOC);

        // Check if $data has zero rows
        if ($stmTeam->rowCount() != 0) {
            $data = array_merge($dataClub, $dataTeam);
            sendSuccessResponse('200', 'Success', $data);
        }
    }
    
    sendSuccessResponse('200', 'Success', $dataClub);


} else if($route == 'getEventsClub') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        sendErrorResponse('400', 'Invalid JSON data!');
    }

    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';

    if($idClub == ""){
        sendErrorResponse('400', 'You must have a Club!');
    }

    try{
        $sql = "SELECT * FROM Events WHERE idClub = :idClub";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
        $result = $stm->execute();
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);
        
        if(empty($data)){
            sendErrorResponse('400', 'Doesnt\'t exist events of this club!');
        }

        sendSuccessResponse('200', 'Success', $data);

    } catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }

} else {
    // Handle 404 Not Found
    http_response_code(404);
    echo 'Route not found!';  
}