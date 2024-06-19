<?php
// session_start(); // Start or resume the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// echo 'Current rota: ' . $_SESSION['rota'];


// switch (isset($_SESSION['rota']) ? $_SESSION['rota'] : null) {

// echo 'Current rota: ' . ($_SESSION['rota'] ?? 'not set');

// $route = isset($_SESSION['route']) ? $_SESSION['route'] : 'dashboard';

// switch ($route) {
    // case 'dashboard':
    //     include './dashboard.php';
    //     break;
    // case 'clubs':
    //     include './tables/clubs.php';
    //     break;
    // case 'teams':
    //     include './tables/teams.php';
    //     break;
    // case 'coaches':
    //     include './tables/coaches.php';
    //     break;
    // case 'players':
    //     include './tables/players.php';
    //     break;
    // default:
    //     include './tables/clubs.php';
    //     break;
// }


if (isset($_SESSION['route'])) {
    switch ($_SESSION['route']) {
        case 'dashboard':
            include './dashboard.php';
            break;
        case 'clubs':
            include './tables/clubs.php';
            break;
        case 'club':
            include './club.php';
            break;
        case 'teams':
            include './teams.php';
            break;
        case 'coaches':
            include './tables/coaches.php';
            break;
        case 'players':
            include './tables/players.php';
            break;
        case 'coachesClub':
            include './coaches.php';
            break;
        case 'playersClub':
            include './players.php';
            break;
        case 'requestsClub':
            include './requests.php';
            break;
        case 'clothesClub':
            include './clothes.php';
            break;
        default:
            include './dashboard.php';
            break;
    }
} else {
    $_SESSION['route'] = 'dashboard';
}
?>
