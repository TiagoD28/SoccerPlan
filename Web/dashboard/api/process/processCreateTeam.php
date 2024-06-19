<?php
require_once '../../api/requests/sendData.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$data = [];

if (isset($_POST['createTeam'])) {
    // Get the values from the form
    $nameTeam = $_POST['nameTeam'];
    $age = $_POST['agesDropdown'];
    $fieldOf = $_POST['fieldsDropdown'];
    $rank = $_POST['ranksDropdown'];
    $ab = $_POST['abDropdown'];
    $coach = $_POST['coachesDropdown'];
    $championship = $_POST['championshipsDropdown'];
    $idClub = $_SESSION['idClub'];

    $data = [
        'nameTeam' => $nameTeam,
        'age' => $age,
        'fieldOf' => $fieldOf,
        'rank' => $rank,
        'ab' => $ab,
        'idCoach' => $coach,
        'idChampionship' => $championship,
        'idClub' => $idClub
    ];
}


$route = 'Teams/index.php?route=createTeamClub';

$apiResponse = sendDataToApi($route, $data);
$decodedResponse = json_decode($apiResponse, true);
$_SESSION['toast'] = $decodedResponse['status'];
$_SESSION['toastMessage'] = $decodedResponse['message'];

header('Location: ../../views/adminClub/base.php?route=teams');
exit;
?>