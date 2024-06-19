<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // $login = true;
    // $userType = 'adminClub';
    // if this admin club doesn t have a club redirect to a page to register the club else
    // get the idClub of this adm

    if (isset($_SESSION['email']) && !empty($_SESSION['email']) && isset($_SESSION['typeUser'])) {
    // if ($login == true && $userType == 'adminClub') {

        if($_SESSION['typeUser'] == 'Admin'){
            header("Location: ./views/admin/base.php");
            exit();
            
        } else if($_SESSION['typeUser'] == 'ClubAdmin') {
            if($_SESSION['idClub'] == ''){
                // echo $_SESSION['idClub'];
                header("Location: ./views/adminClub/createClub.php");
                exit();
            } else {
                echo $_SESSION['idClub'];
                header("Location: ./views/adminClub/base.php");
                exit();
            }
            
        } else if($_SESSION['typeUser'] == 'Employer'){
            header("Location: ./views/adminClub/base.php");
            exit();
        }
    
    } else {
        // User is not logged in, redirect to the login page
        header("Location: ./views/authentication/login.php");
        exit();
    }
?>

<?php
    // session_start();

    // // $login = true;
    // // $userType = 'adminClub';
    // // if this admin club doesn t have a club redirect to a page to register the club else
    // // get the idClub of this adm

    // if (isset($_SESSION['email']) && !empty($_SESSION['email']) &&
    // isset($_SESSION['typeUser']) && $_SESSION['typeUser'] == 'Admin') {
    // // if ($login == true && $userType == 'adminClub') {
    //     header("Location: ./views/adminClub/base.php");
    //     exit();
    // } 
    // else if (isset($_SESSION['email']) && !empty($_SESSION['email']) && 
    // isset($_SESSION['typeUser']) && ($_SESSION['typeUser'] == 'ClubAdmin' || $_SESSION['typeUser'] == 'Employer')) {
    //     header("Location: ./views/adminClub/base.php");
    //     exit();

    // } 
    // else {
    //     // User is not logged in, redirect to the login page
    //     header("Location: ./views/login.php");
    //     exit();
    // }
?>