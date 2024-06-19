<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_POST['createClub'])){
        $nameClub = $_POST['nameClub'];
        $foundedYear = $_POST['foundedYear'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $img = $_POST['img'];
        $idClubAdmin = $_SESSION['idClubAdmin'];
    }

    $data = [
        'nameClub' => $nameClub, // change to a variable that gets the value of typeUser
        'foundedYear' => $foundedYear,
        'city' => $city,
        'country' => $country,
        'img' => $img,
        'idClubAdmin' => $idClubAdmin
    ];

    $route = 'Clubs/index.php?route=createClub';

    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);
    echo $decodedResponse['status'];

    $_SESSION['status'] = $decodedResponse['status'];
    $_SESSION['message'] = $decodedResponse['message'];

    switch ($decodedResponse['status']) {
        case '400-1':
            $_SESSION['toast'] = '400';
            header('Location: ../../views/adminClub/createClub.php');
            break;
        case '400-2':
            $_SESSION['toast'] = '400';
            header('Location: ../../views/adminClub/createClub.php');
            break;
        case '400-3':
            $_SESSION['toast'] = '400';
            header('Location: ../../views/adminClub/createClub.php');
            break;
        case '400-4':
            $_SESSION['toast'] = '400';
            header('Location: ../../views/adminClub/createClub.php');
            break;
        case '400-5':
            $_SESSION['toast'] = '400';
            header('Location: ../../views/adminClub/createClub.php');
            break;
        default:
            $_SESSION['toast'] = '200';
            $_SESSION['idClub'] = $responseData['idClub'];
            header('Location: ../../views/adminClub/base.php');
            break;
    }
?>