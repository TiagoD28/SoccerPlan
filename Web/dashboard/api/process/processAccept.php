<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_GET['idRequestClub'])){
        $idRequestClub = $_GET['idRequestClub'];
        $idRequester = $_GET['idRequester'];
        $idClub = $_GET['idClub'];

        $data = [
            'idRequestClub' => $idRequestClub, 
            'idRequester' => $idRequester,
            'idClub' => $idClub,
            'state' => 'accepted'
        ];
    
        $route = 'Requests/index.php?route=answerRequestClub';
    
        $apiResponse = sendDataToApi($route, $data);
        $decodedResponse = json_decode($apiResponse, true);
        // echo $decodedResponse['status'];
    
        $_SESSION['status'] = $decodedResponse['status'];
        $_SESSION['message'] = $decodedResponse['message'];
        $_SESSION['toastMessage'] = $_SESSION['message'];
        // $_SESSION['toast'] = '400';
        switch ($decodedResponse['status']) {
            case '400':
                $_SESSION['toast'] = '400';
                header('Location: ../../views/adminClub/base.php?route=requestsClub');
                break;
            case '400':
                $_SESSION['toast'] = '400';
                header('Location: ../../views/adminClub/base.php?route=requestsClub');
                break;
            default:
                $_SESSION['toast'] = '200';
                header('Location: ../../views/adminClub/base.php?route=requestsClub');
                break;
        }

    } else if(isset($_GET['idRequestTeam'])){
        $idRequestTeam = $_GET['idRequestTeam'];
        $idRequester = $_GET['idRequester'];
        $idClub = $_GET['idClub'];
        $idTeam = $_GET['idTeam'];

        $data = [
            'idRequestTeam' => $idRequestTeam, 
            'idRequester' => $idRequester,
            'idClub' => $idClub,
            'idTeam' => $idTeam,
            'state' => 'accepted'
        ];
    
        $route = 'Requests/index.php?route=answerRequestTeam';
    
        $apiResponse = sendDataToApi($route, $data);
        $decodedResponse = json_decode($apiResponse, true);
        echo $decodedResponse['status'];
    
        $_SESSION['status'] = $decodedResponse['status'];
        $_SESSION['message'] = $decodedResponse['message'];
        // $_SESSION['toast'] = '400';
        switch ($decodedResponse['status']) {
            case '400':
                $_SESSION['toast'] = '400';
                $_SESSION['toastMessage'] = $_SESSION['message'];
                header('Location: ../../views/adminClub/base.php?route=requestsClub');
                break;
            case '400':
                $_SESSION['toast'] = '400';
                $_SESSION['toastMessage'] = $_SESSION['message'];
                header('Location: ../../views/adminClub/base.php?route=requestsClub');
                break;
            default:
                $_SESSION['toast'] = '200';
                $_SESSION['toastMessage'] = $_SESSION['message'];
                header('Location: ../../views/adminClub/base.php?route=requestsClub');
                break;
        }

    }


?>