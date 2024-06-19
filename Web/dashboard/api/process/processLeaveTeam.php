<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_GET['idCoach'])){
        $idCoach = $_GET['idCoach'];
        $idTeam = $_GET['idTeam'];

        $data = [
            'idCoach' => $idCoach,
            'idTeam' => $idTeam,
        ];
    
        $route = 'Coaches/index.php?route=leaveTeam';
    
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
        $idTeam = $_GET['idTeam'];

        $data = [
            'idPlayer' => $idPlayer,
            'idTeam' => $idTeam,
        ];
    
        $route = 'Players/index.php?route=leaveTeam';
    
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