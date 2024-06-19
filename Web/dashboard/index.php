<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['email']) && !empty($_SESSION['email']) && isset($_SESSION['typeUser'])) {
        if($_SESSION['typeUser'] == 'ClubAdmin') {
            if($_SESSION['idClub'] == ''){
                // echo $_SESSION['idClub'];
                header("Location: ./views/authentication/createClub.php");
                exit();
            } else {
                echo $_SESSION['idClub'];
                header("Location: ./views/adminClub/base.php?route=club");
                exit();
            }
            
        } else if($_SESSION['typeUser'] == 'Employer'){
            if($_SESSION['idClub'] == ''){
                // echo $_SESSION['idClub'];
                header("Location: ./views/adminClub/base.php?route=club");
                exit();
            } else {
                header("Location: ./views/adminClub/base.php?route=club");
                exit();
            }
        }
    
    } else {
        // User is not logged in, redirect to the login page
        header("Location: ./views/authentication/login.php");
        exit();
    }
?>