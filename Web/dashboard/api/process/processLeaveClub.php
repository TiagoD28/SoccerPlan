<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_GET['idCoach'])){
        $idCoach = $_GET['idCoach'];
        $idClub = $_GET['idClub'];

        $data = [
            'idCoach' => $idCoach,
            'idClub' => $idClub,
        ];
    
        $route = 'Coaches/index.php?route=leaveClub';
    
        $apiResponse = sendDataToApi($route, $data);
        $decodedResponse = json_decode($apiResponse, true);
    
        $_SESSION['status'] = $decodedResponse['status'];
        $_SESSION['message'] = $decodedResponse['message'];
        $_SESSION['toastMessage'] = $_SESSION['message'];

        switch ($decodedResponse['status']) {
            case '400':
                $_SESSION['toast'] = '400';
                header('Location: ../../views/adminClub/base.php?route=coaches');
                break;
            case '400':
                $_SESSION['toast'] = '400';
                header('Location: ../../views/adminClub/base.php?route=coaches');
                break;
            default:
                $_SESSION['toast'] = '200';
                header('Location: ../../views/adminClub/base.php?route=coaches');
                break;
        }

    } else if(isset($_GET['idPlayer'])){
        $idPlayer = $_GET['idPlayer'];
        $idClub = $_GET['idClub'];

        $data = [
            'idPlayer' => $idPlayer,
            'idClub' => $idClub,
        ];
    
        $route = 'Players/index.php?route=leaveClub';
    
        $apiResponse = sendDataToApi($route, $data);
        $decodedResponse = json_decode($apiResponse, true);
        echo $decodedResponse['status'];
    
        $_SESSION['status'] = $decodedResponse['status'];
        $_SESSION['message'] = $decodedResponse['message'];
        switch ($decodedResponse['status']) {
            case '400':
                $_SESSION['toast'] = '400';
                $_SESSION['toastMessage'] = $_SESSION['message'];
                header('Location: ../../views/adminClub/base.php?route=players');
                break;
            case '400':
                $_SESSION['toast'] = '400';
                $_SESSION['toastMessage'] = $_SESSION['message'];
                header('Location: ../../views/adminClub/base.php?route=players');
                break;
            default:
                $_SESSION['toast'] = '200';
                $_SESSION['toastMessage'] = $_SESSION['message'];
                header('Location: ../../views/adminClub/base.php?route=players');
                break;
        }

    }

?>