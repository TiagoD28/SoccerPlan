<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

function clubExists($name, $pdo){
    $sql = "SELECT nameClub FROM Clubs WHERE nameClub = :nameClub";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':nameClub', $name, PDO::PARAM_STR);
    $stm->execute();

    $count = $stm->fetchColumn();

    return $count > 0;
}

if($route == 'createClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    
    $nameClub = isset($requestData['nameClub']) ? $requestData['nameClub'] : '';
    $foundedYear = isset($requestData['foundedYear']) ? $requestData['foundedYear'] : '';
    $city = isset($requestData['city']) ? $requestData['city'] : '';
    $country = isset($requestData['country']) ? $requestData['country'] : '';
    $img = isset($requestData['img']) ? $requestData['img'] : '';
    $phoneNumber = isset($requestData['phoneNumber']) ? $requestData['phoneNumber'] : '';
    $email = isset($requestData['email']) ? $requestData['email'] : '';
    $idClubAdmin = isset($requestData['idClubAdmin']) ? $requestData['idClubAdmin'] : '';

    if($nameClub == ''){
        sendErrorResponse('400-1', 'Name must be introduced!'); 
    }
    if($foundedYear == ''){
        sendErrorResponse('400-2', 'Founded Year must be introduced!');
    }
    if($city == ''){
        sendErrorResponse('400-3', 'City must be introduced!');
    }
    if($country == ''){
        sendErrorResponse('400-4', 'Country must be introduced!');
    }
    if(clubExists($nameClub, $pdo)){
        sendErrorResponse('400-5', 'Name of club already exist!');
    }
    
    try{
        $sql = "INSERT INTO Clubs (nameClub, foundedYear, city, country, img, idClubAdmin)
        VALUES (:nameClub, :foundedYear, :city, :country, :img, :idClubAdmin)";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':nameClub', $nameClub, PDO::PARAM_STR);
        $stm->bindParam(':foundedYear', $foundedYear, PDO::PARAM_INT);
        $stm->bindParam(':city', $city, PDO::PARAM_STR);
        $stm->bindParam(':country', $country, PDO::PARAM_STR);
        $stm->bindParam(':img', $img, PDO::PARAM_STR);
        $stm->bindParam(':idClubAdmin', $idClubAdmin, PDO::PARAM_INT);
        $stm->execute();


        $sql1 = "SELECT * FROM Clubs ORDER BY idClub DESC LIMIT 1";
        $stm1 = $pdo->prepare($sql1);
        $stm1->execute();
        $data = $stm1->fetch(PDO::FETCH_ASSOC);


        sendSuccessResponse('200', 'Clube created successfuly!', $data);
    }catch(PDOException $e){
        $errorMessage = 'Error: ' . addslashes(htmlspecialchars($e->getMessage()));
        sendErrorResponse('400', $errorMessage);
    }


} else if($route == 'getClubs'){
    $sql = "SELECT * FROM Clubs";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $data = $stm->fetchAll(PDO::FETCH_ASSOC);
    sendSuccessResponse('200', 'Success', $data);

} else if($route == 'deleteClub'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }

    $tablename = $requestData['tablename'];
    $key = $requestData['key'];
    $value = $requestData['value'];

    $sql = "DELETE FROM $tablename WHERE $key = :idClub";
    $stm = $pdo ->prepare($sql);
    $stm->bindParam(':idClub', $value, PDO::PARAM_STR);
    $stm->execute();

    sendSuccessResponse(200, 'Row deleted successfuly', '');
}