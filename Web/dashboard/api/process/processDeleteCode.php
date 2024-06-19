<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $club = false; // to send api if its a code club or a code team

    if(isset($_GET['idCodeClub'])){
        $idCode = $_GET['idCodeClub'];
        $club = true;

        $data = [
            'idCode' => $idCode,
            'club' => $club
        ];
    
        $route = 'Codes/index.php?route=deleteCode';
    
        $apiResponse = sendDataToApi($route, $data);
        $decodedResponse = json_decode($apiResponse, true);
    
        $_SESSION['status'] = $decodedResponse['status'];
        $_SESSION['message'] = $decodedResponse['message'];
        $_SESSION['toastMessage'] = $_SESSION['message'];

        switch ($decodedResponse['status']) {
            case '400':
                $_SESSION['toast'] = '400';
                header('Location: ../../views/adminClub/base.php?route=codes');
                break;
            case '400':
                $_SESSION['toast'] = '400';
                header('Location: ../../views/adminClub/base.php?route=codes');
                break;
            default:
                $_SESSION['toast'] = '200';
                header('Location: ../../views/adminClub/base.php?route=codes');
                break;
        }

    } else if(isset($_GET['idCodeTeam'])){
        $idCode = $_GET['idCodeTeam'];

        $data = [
            'idCode' => $idCode,
            'club' => $club
        ];
    
        $route = 'Codes/index.php?route=deleteCode';
    
        $apiResponse = sendDataToApi($route, $data);
        $decodedResponse = json_decode($apiResponse, true);
        echo $decodedResponse['status'];
    
        $_SESSION['status'] = $decodedResponse['status'];
        $_SESSION['message'] = $decodedResponse['message'];
        
        switch ($decodedResponse['status']) {
            case '400':
                $_SESSION['toast'] = '400';
                $_SESSION['toastMessage'] = $_SESSION['message'];
                header('Location: ../../views/adminClub/base.php?route=codes');
                break;
            case '400':
                $_SESSION['toast'] = '400';
                $_SESSION['toastMessage'] = $_SESSION['message'];
                header('Location: ../../views/adminClub/base.php?route=codes');
                break;
            default:
                $_SESSION['toast'] = '200';
                $_SESSION['toastMessage'] = $_SESSION['message'];
                header('Location: ../../views/adminClub/base.php?route=codes');
                break;
        }

    }


?>