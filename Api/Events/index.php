<?php
// Load your database connection and data.php
require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

if($route == 'addEvent'){
    // file_get_contents("php://input"): This PHP function reads raw POST data from the input stream. 
    // In the context of an HTTP POST request with a JSON payload, this would be the JSON data.
    // json_decode(..., true): This PHP function decodes a JSON string into a PHP associative array. 
    // The second parameter true indicates that the function should return an associative array instead of an object.
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        header('Content-Type: application/json');
        echo json_encode([
            'status' => '400',
            'message' => 'Invalid JSON data',
        ]);
        exit;
    }

    // Extract data fields
    $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    // $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';
    $typeEvent = isset($requestData['typeEvent']) ? $requestData['typeEvent'] : '';
    $startDate = isset($requestData['startDate']) ? $requestData['startDate'] : '';
    $endDate = isset($requestData['endDate']) ? $requestData['endDate'] : '';
    $meetTime = isset($requestData['meetTime']) ? $requestData['meetTime'] : '';
    $locall = isset($requestData['local']) ? $requestData['local'] : '';
    $meetingLocal = isset($requestData['meetingLocal']) ? $requestData['meetingLocal'] : '';

    // $errors = false;
    if($typeEvent == ''){
        // $errors = true;
        sendErrorResponse('400', 'Type event must be selected!'); 
    }
    if($startDate == ''){
        // $errors = true;
        sendErrorResponse('400', 'Start date must be selected!'); 
    }
    if($endDate == ''){
        // $errors = true;
        sendErrorResponse('400', 'End date must be selected!!');
    }
    if($meetTime == ''){
        // $errors = true;
        sendErrorResponse('400', 'Meeting time must be selected!');
    }
    if($locall == ''){
        // $errors = true;
        sendErrorResponse('400', 'Local must be defined!');
    }
    if($meetingLocal == ''){
        // $errors = true;
        sendErrorResponse('400', 'Meeting local must be defined!');
    }
    if($idClub == ''){
        // $errors = true;
        sendErrorResponse('400', 'Id Club must be defined!');
    }

    // im setting idTeam for testing
    // $idTeam = NULL;



    // Insert data into the 'Events' table
    $sql = "INSERT INTO Events (typeEvent, startDate, endDate, meetTime, locall, meetingLocal, idUser, idClub)
    VALUES (:typeEvent, :startDate, :endDate, :meetTime, :locall, :meetingLocal, :idUser, :idClub)";
    $stm = $pdo ->prepare($sql);
    $stm->bindParam(':typeEvent', $typeEvent, PDO::PARAM_STR);
    $stm->bindParam(':startDate', $startDate, PDO::PARAM_STR);
    $stm->bindParam(':endDate', $endDate, PDO::PARAM_STR);
    $stm->bindParam(':meetTime', $meetTime, PDO::PARAM_STR);
    $stm->bindParam(':locall', $locall, PDO::PARAM_STR);
    $stm->bindParam(':meetingLocal', $meetingLocal, PDO::PARAM_STR);
    $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    // $stm->bindParam(':idTeam', $idTeam, PDO::PARAM_INT);
    
    $result = $stm->execute();
    if ($result) {
        // header('Content-Type: application/json');
        sendSuccessResponse('200', 'Event added successfuly!', '');
    } else {
        // header('Content-Type: application/json');
        sendErrorResponse('500', 'Failed to add event!');
    }

} else if($route == 'getEvents') {
    $sql = "SELECT * FROM Events";
    $stm = $pdo->prepare($sql);
    $result = $stm->execute();
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => '200',
        'message' => 'sucesso',
        'data' => $data,
    ]);
} else if($route == 'getPastEvents') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        header('Content-Type: application/json');
        echo json_encode([
            'status' => '400',
            'message' => 'Invalid JSON data',
        ]);
        exit;
    }

    // Extract data fields
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

    // header('Content-Type: application/json');
    // echo json_encode([
    //     'status' => '200',
    //     'message' => 'sucesso',
    //     'data' => $data,
    // ]);
} else if($route == 'getNextEvents') {

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        // header('Content-Type: application/json');
        // echo json_encode([
        //     'status' => '400',
        //     'message' => 'Invalid JSON data',
        // ]);
        sendErrorResponse('400', 'Invalid JSON data!');
    }

    // Extract data fields
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

    // header('Content-Type: application/json');
    // echo json_encode([
    //     'status' => '200',
    //     'message' => 'sucesso',
    //     'data' => $data,
    // ]);


    // $requestData = json_decode(file_get_contents("php://input"), true);
    // if ($requestData === null) {
    //     // Handle JSON decoding error
    //     header('Content-Type: application/json');
    //     echo json_encode([
    //         'status' => '400',
    //         'message' => 'Invalid JSON data',
    //     ]);
    //     exit;
    // }

    // // Extract data fields
    // $idUser = isset($requestData['idUser']) ? $requestData['idUser'] : '';
    // $idClub = isset($requestData['idClub']) ? $requestData['idClub'] : '';
    // // $idTeam = isset($requestData['idTeam']) ? $requestData['idTeam'] : '';

    // // this only gets the next events created by idUser that its logged
    // $sql = "SELECT * FROM Events WHERE (idUser = :idUser AND idClub = :idClub) AND STR_TO_DATE(startDate, '%d-%m-%Y') > CURDATE()";
    // $stm = $pdo->prepare($sql);
    // $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    // $stm->bindParam(':idClub', $idClub, PDO::PARAM_INT);
    // // $stm->bindParam(':endDate', $endDate, PDO::PARAM_STR);
    // $result = $stm->execute();
    // $data = $stm->fetchAll(PDO::FETCH_ASSOC);

    // $finalData = [];

    // // Check if $data has zero rows
    // if ($stm->rowCount() == 0) {
    //     sendErrorResponse('400', 'There is no next events');
    // } else {
    //     // if exists next events i need to check if the time as passed

    //     // $currentTime = date('H:i');

    //     // foreach ($data as $key => $value) {
    //     //     $meetTime = $value['meetTime']; // Assuming 'meetTime' is the column name

    //     //     list($currentHour, $currentMinute) = explode(':', $currentTime);
    //     //     list($meetHour, $meetMinute) = explode(':', $meetTime);

    //     //     // Convert hours and minutes to integers for comparison
    //     //     $currentHour = intval($currentHour);
    //     //     $currentMinute = intval($currentMinute);
    //     //     $meetHour = intval($meetHour);
    //     //     $meetMinute = intval($meetMinute);

    //     //     // Compare hours
    //     //     if ($meetHour > $currentHour || ($meetHour == $currentHour && $meetMinute > $currentMinute)) {
    //     //         // Event is in the future, add the entire row to $finalData
    //     //         $finalData[$key] = $value;
    //     //         sendSuccessResponse('200', 'asdf', $finalData);
    //     //         // $finalData[] = $value;
    //     //     }
    //     // }
    // }

    // sendSuccessResponse('200', 'Get Next Events successful!', $data);

    // header('Content-Type: application/json');
    // echo json_encode([
    //     'status' => '200',
    //     'message' => 'sucesso',
    //     'data' => $data,
    // ]);
} 
else {
    // Handle 404 Not Found
    http_response_code(404);
    echo 'Route not found!';  
}