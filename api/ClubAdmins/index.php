<?php

require_once('../Connection/data.php');
require_once('../Response/index.php');

$route = isset($_GET['route']) ? $_GET['route'] : null;

if($route == 'getClubWeb'){
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
    }
    
    $idCoach = isset($requestData['idCoach']) ? $requestData['idCoach'] : '';

    if(isset($requestData['idClubAdmin'])){
        $idClubAdmin = $requestData['idClubAdmin'];

        $sql = "SELECT * FROM Clubs WHERE idClubAdmin = :idClubAdmin";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':idClubAdmin', $idClubAdmin, PDO::PARAM_STR);
        $stm->execute();
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) == 0) {
            // No clubs found, send error response
            echo $idClubAdmin;
            sendErrorResponse('404', 'Club Administrator doesn t have a club!');
        }

        sendSuccessResponse('200', 'Success', $data);
    } else if(isset($requestData['idEmployer'])){
        $idClub = $requestData['idClub'];

        $sql = "SELECT * FROM Clubs WHERE idClub = :idClub";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':idClub', $idClub, PDO::PARAM_STR);
        $stm->execute();
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) == 0) {
            // No clubs found, send error response
            sendErrorResponse('400', 'Employer doesn t have a club!');
        }

        sendSuccessResponse('200', 'Success', $data);
    }
}