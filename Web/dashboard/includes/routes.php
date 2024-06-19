<?php
// session_start(); // Start or resume the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['route'])) {
    switch ($_SESSION['route']) {
        case 'club':
            include './club.php';
            break;
        case 'teams':
            include './teams.php';
            break;
        case 'coaches':
            include './coaches.php';
            break;
        case 'players':
            include './players.php';
            break;
        case 'requests':
            include './requests.php';
            break;
        case 'clothes':
            include './clothes.php';
            break;
        case 'codes':
            include './codes.php';
            break;
        case 'calendar':
            include './calendar.php';
            break;
        case 'championships':
            include './championships.php';
            break;
        default:
            include './club.php';
            break;
    }
} else {
    $_SESSION['route'] = 'dashboard';
}
?>
