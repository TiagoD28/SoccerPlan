<?php
require_once '../../api/requests/sendData.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$data = [];

if (isset($_POST['createEvent'])) {
    // Get the values from the form
    $eventType = $_POST['eventType'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $meetTime = $_POST['meetTime'];
    $meetLocal = $_POST['meetLocal'];
    $stadium = $_POST['stadium'];
    $idTeam = $_POST['teamsDropdown'];
    $idClub = $_SESSION['idClub'];
    $idUser = $_SESSION['idUser'];

    $data = [
        'typeEvent' => $eventType,
        'startDate' => date("d-m-Y", strtotime($startDate)),
        'endDate' => date("d-m-Y", strtotime($endDate)),
        'meetTime' => $meetTime,
        'meetingLocal' => $meetLocal,
        'local' => $stadium,
        'idTeam' => $idTeam,
        'idClub' => $idClub,
        'idUser' => $idUser
    ];
}

// Now $data is defined in all code paths
$route = 'Events/index.php?route=addEvent';

$apiResponse = sendDataToApi($route, $data);
$decodedResponse = json_decode($apiResponse, true);
$_SESSION['toast'] = $decodedResponse['status'];
$_SESSION['toastMessage'] = $decodedResponse['message'];

header('Location: ../../views/adminClub/base.php?route=calendar');
?>