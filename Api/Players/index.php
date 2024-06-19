<?php
// Load your database connection and data.php
require_once('../Connection/data.php');
require_once('../Response/index.php');

// $route = $_GET['route'];
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
    $sqlPlayers = "SELECT * FROM players";
    $stmPlayers = $pdo->prepare($sqlPlayers);
    $stmPlayers->execute();

    $dataPlayers = $stmPlayers->fetchAll(PDO::FETCH_ASSOC);

    // Extracting idUser values from the fetched data
    $idUserValues = array_column($dataPlayers, 'idUser');

    // Fetching additional data from the "users" table based on idUser values
    $sqlUsers = "SELECT * FROM users WHERE idUser IN (" . implode(',', $idUserValues) . ")";
    $stmUsers = $pdo->prepare($sqlUsers);
    $stmUsers->execute();

    $dataUsers = $stmUsers->fetchAll(PDO::FETCH_ASSOC);

    $mergedData = array();
    foreach ($dataPlayers as $player) {
        $mergedData[] = $player + findUserById($dataUsers, $player['idUser']);
    }

    // Output or use the fetched data from both tables as needed
    // var_dump($dataCoaches);
    // var_dump($dataUsers);

    // $allData = array_merge($dataCoaches, $dataUsers);

    
    sendSuccessResponse('200', 'Success getting all coaches!', $mergedData);

} else {
    // Handle 404 Not Found
    http_response_code(404);
    echo 'Route not found!';  
}