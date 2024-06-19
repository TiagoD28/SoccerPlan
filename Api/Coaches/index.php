<?php

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

if($route == 'getCoach'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';

    // data of table Coaches
    $sql = "SELECT * FROM Coaches WHERE idCoach = :idCoach";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
    $stm->execute();

    $dataCoach = $stm->fetchAll(PDO::FETCH_ASSOC);

    //data of table Teams
    $sql = "SELECT idTeam FROM Teams WHERE idCoach = :idCoach";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
    $stm->execute();

    $dataTeam = $stm->fetchAll(PDO::FETCH_ASSOC);

    $allData = array_merge($dataCoach, $dataTeam);

    sendSuccessResponse('200', 'Success', $allData);

    // header('Content-Type: application/json');
    // echo json_encode([
    //     'status' => '200',
    //     'message' => 'sucesso',
    //     'data' => $allData
    // ]);
} else if($route === 'getCoaches'){
    $sqlCoaches = "SELECT * FROM coaches";
    $stmCoaches = $pdo->prepare($sqlCoaches);
    $stmCoaches->execute();

    $dataCoaches = $stmCoaches->fetchAll(PDO::FETCH_ASSOC);

    // Extracting idUser values from the fetched data
    $idUserValues = array_column($dataCoaches, 'idUser');

    // Fetching additional data from the "users" table based on idUser values
    $sqlUsers = "SELECT * FROM users WHERE idUser IN (" . implode(',', $idUserValues) . ")";
    $stmUsers = $pdo->prepare($sqlUsers);
    $stmUsers->execute();

    $dataUsers = $stmUsers->fetchAll(PDO::FETCH_ASSOC);

    $mergedData = array();
    foreach ($dataCoaches as $coach) {
        $mergedData[] = $coach + findUserById($dataUsers, $coach['idUser']);
    }

    // Output or use the fetched data from both tables as needed
    // var_dump($dataCoaches);
    // var_dump($dataUsers);

    // $allData = array_merge($dataCoaches, $dataUsers);

    
    sendSuccessResponse('200', 'Success getting all coaches!', $mergedData);
}
// else if($route === 'getAllInfoCoach'){
//     $requestData = json_decode(file_get_contents("php://input"), true);
//     if ($requestData === null) {
//         // Handle JSON decoding error
//         sendErrorResponse('400', 'Invalide JSON data');
//         exit;
//     }

//     $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';

//     // data of table Coaches
//     $sql = "SELECT * FROM Coaches WHERE idCoach = :idCoach";
//     $stm = $pdo->prepare($sql);
//     $stm->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
//     $stm->execute();

//     $dataCoach = $stm->fetchAll(PDO::FETCH_ASSOC);

//     //data of table Teams
//     $sql = "SELECT idTeam FROM Teams WHERE idCoach = :idCoach";
//     $stm = $pdo->prepare($sql);
//     $stm->bindParam(':idCoach', $idCoach, PDO::PARAM_INT);
//     $stm->execute();

//     $dataTeam = $stm->fetchAll(PDO::FETCH_ASSOC);

//     sendSuccessResponse('200', 'Success', );
// }