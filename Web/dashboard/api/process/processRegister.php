<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_POST['register'])){
        $typeUser = $_POST['typeUser'];
        $username = $_POST['username'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
    }

    $data = [
        'typeUser' => $typeUser, // change to a variable that gets the value of typeUser
        'username' => $username,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'password' => $password
    ];

    $route = 'Authentication/index.php?route=register';

    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);
    echo $decodedResponse['status'];
    $_SESSION['toastMessage'] = $decodedResponse['message'];

    switch ($decodedResponse['status']) {
        case '400-1':
            $_SESSION['toast'] = '400';
            header('Location: ../../views/authentication/register.php');
            break;
        case '400-2':
            $_SESSION['toast'] = '400';
            header('Location: ../../views/authentication/register.php');
            break;
        case '400-3':
            $_SESSION['toast'] = '400';
            header('Location: ../../views/authentication/register.php');
            break;
        case '400-4':
            $_SESSION['toast'] = '400';
            header('Location: ../../views/authentication/register.php');
            break;
        case '400-5':
            $_SESSION['toast'] = '400';
            header('Location: ../../views/authentication/register.php');
            break;
        case '400-6':
            $_SESSION['toast'] = '400';
            header('Location: ../../views/authentication/register.php');
            break;
        case '400-7':
            $_SESSION['toast'] = '400';
            header('Location: ../../views/authentication/register.php');
            break;
        default:
            $_SESSION['toast'] = '200';
            header('Location: ../../views/authentication/login.php');
            break;
    }
?>