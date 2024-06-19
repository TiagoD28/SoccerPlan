<?php
// Load your database connection and data.php
// use \Firebase\JWT\JWT;
require_once('../Connection/data.php');
require_once('../Response/index.php');


// $route = $_GET['route'];
$route = isset($_GET['route']) ? $_GET['route'] : null;

function emailExists($email, $pdo){
    $sql = "SELECT email FROM Users WHERE email = :email";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':email', $email, PDO::PARAM_STR);
    $stm->execute();

    $count = $stm->fetchColumn();

    return $count > 0;
}

function userExists($username, $pdo){
    $sql = "SELECT username FROM Users WHERE username = :username";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':username', $username, PDO::PARAM_STR);
    $stm->execute();

    $count = $stm->fetchColumn();

    return $count > 0;
}

function createACPE($typeUser, $email, $pdo){
    // Check idUser to create Player
    $sql = "SELECT idUser FROM Users WHERE email = :email";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':email', $email, PDO::PARAM_STR);
    $stm->execute();

    $row = $stm->fetch(PDO::FETCH_ASSOC);

    $idUser = $row['idUser'];

    if($typeUser === 'Player'){
        $sql = "INSERT INTO Players (idUser)
        VALUES (:idUser)";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stm->execute(); 
    }
    if($typeUser === 'Admin'){
        $sql = "INSERT INTO Admins (idUser)
        VALUES (:idUser)";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stm->execute(); 
    }
    if($typeUser === 'ClubAdmin'){
        $sql = "INSERT INTO ClubAdmins (idUser)
        VALUES (:idUser)";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stm->execute(); 
    }
    if($typeUser === 'Coach'){
        $sql = "INSERT INTO Coaches (idUser)
        VALUES (:idUser)";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stm->execute(); 
    }
    if($typeUser === 'Employer'){
        $sql = "INSERT INTO Employers (idUser)
        VALUES (:idUser)";
        $stm = $pdo ->prepare($sql);
        $stm->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stm->execute(); 
    }
}

// function checkUser($typeUser, $user, $pass, $pdo){
function checkUser($user, $pass, $pdo){
    // $sql = "SELECT * FROM Users WHERE (typeUser = :typeUser AND (email = :user OR username = :user))";
    $sql = "SELECT * FROM Users WHERE (email = :user OR username = :user)";
    $stm = $pdo->prepare($sql);
    // $stm->bindParam(':typeUser', $typeUser, PDO::PARAM_STR);
    $stm->bindParam(':user', $user, PDO::PARAM_STR);
    $stm->execute();

    $row = $stm->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        sendErrorResponse('400', 'Doesn t exist this Username or Email!');
    } else {
        if(password_verify($pass, $row['pass'])){
            $data = [];
            foreach ($row as $key => $value) {
                if($key != 'pass'){
                    $data[$key] = $value;
                }
            }

            // sendInfoOfUser($typeUser, $row['idUser'], $data, $pdo);
            sendInfoOfUser($row['typeUser'] ,$row['idUser'], $data, $pdo);

            // sendSuccessResponse('200', 'Login successful!', $data);
        } else {
            sendErrorResponse('400', 'Incorrect password!');
        }
    }
}

function checkUserWeb($user, $pass, $pdo){
    $sql = "SELECT * FROM Users WHERE email = :user OR username = :user";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':user', $user, PDO::PARAM_STR);
    $stm->execute();

    $row = $stm->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        sendErrorResponse('400', 'This email doesn t exist!');
    } else {
        if(password_verify($pass, $row['pass'])){
            $data = [];
            foreach ($row as $key => $value) {
                if($key != 'pass'){
                    $data[$key] = $value;
                }
            }
            
            // echo $row['typeUser'];
            sendInfoOfUser($row['typeUser'], $row['idUser'], $data, $pdo);
        } else {
            sendErrorResponse('400', 'Incorrect password!');
        }
    }
}

function sendInfoOfUser($typeUser, $idUser, $data, $pdo){
    $dataInfo = [];
    $hasClub = false;
    $tablename = '';
    if($typeUser === 'Admin'){
        $tablename = 'Admins';
    }

    if($typeUser === 'ClubAdmin'){
        $tablename = 'ClubAdmins';
    }

    if($typeUser === 'Coach'){
        $tablename = 'Coaches';
    }

    if($typeUser === 'Player'){
        $tablename = 'Players';
    }

    if($typeUser === 'Employer'){
        $tablename = 'Employers';
        // $sql = "SELECT * FROM Employers WHERE idUser = :idUser";
        // $stm = $pdo->prepare($sql);
        // $stm->bindParam(':idUser', $idUser, PDO::PARAM_STR);
        // $stm->execute();

        // $row = $stm->fetch(PDO::FETCH_ASSOC);
    }



    $sql = "SELECT * FROM $tablename WHERE idUser = :idUser";
    $stm = $pdo->prepare($sql);
    $stm->bindParam(':idUser', $idUser, PDO::PARAM_STR);
    $stm->execute();
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    if($typeUser === 'ClubAdmin'){ // if ClubAdmin get row of his Club
        $sqlClub = "SELECT * FROM Clubs WHERE idClubAdmin = :idClubAdmin";
        $stmClub = $pdo->prepare($sqlClub);
        $stmClub->bindParam(':idClubAdmin', $row['idClubAdmin'], PDO::PARAM_INT);
        $stmClub->execute();

        $rowClub = $stmClub->fetch(PDO::FETCH_ASSOC);

        if(!$rowClub){
            $dataInfo['idClub'] = '';
        } else {
            $dataInfo['idClub'] = $rowClub['idClub'];
        }
    }

    if($typeUser == 'Coach'){
        $sqlTeam = "SELECT * FROM Teams WHERE idCoach = :idCoach";
        $stmTeam = $pdo->prepare($sqlTeam);
        $stmTeam->bindParam(':idCoach', $row['idCoach'], PDO::PARAM_INT);
        $stmTeam->execute();

        $rowTeam = $stmTeam->fetch(PDO::FETCH_ASSOC);

        if ($rowTeam !== false) {
            // Merge $rowTeam with $dataInfo
            $dataInfo = array_merge($dataInfo, $rowTeam);
        } else {
            $dataInfo = array_merge($dataInfo, ['idTeam' => '']);
        }
    }

    foreach ($row as $key => $value) {
        $dataInfo[$key] = $value;
    }

    $dataInfo = array_merge($dataInfo, $data);


    sendSuccessResponse('200', 'Login successful!', $dataInfo);
    // echo $row['idClub'];
    // echo $dataInfo;
}

// Define routes and their corresponding actions
 if($route == 'register'){

    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    $typeUser = isset($requestData['typeUser']) ? $requestData['typeUser'] : '';
    $email = isset($requestData['email']) ? filter_var($requestData['email'], FILTER_SANITIZE_EMAIL) : '';
    $firstName = isset($requestData['firstName']) ? $requestData['firstName'] : '';
    $lastName = isset($requestData['lastName']) ? $requestData['lastName'] : '';
    $password = isset($requestData['password']) ? $requestData['password'] : '';
    $username = isset($requestData['username']) ? $requestData['username'] : '';

    $errors = false;
    if($typeUser == ''){
        $errors = true;
        sendErrorResponse('400-1', 'Type user must be selected!'); 
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors = true;
        sendErrorResponse('400-2', 'Email is not valid!'); 
    }
    if($firstName == ''){
        $errors = true;
        sendErrorResponse('400-3', 'First name must be defined!');
    }
    if($lastName == ''){
        $errors = true;
        sendErrorResponse('400-4', 'Last name must be defined!');
    }
    if(strlen($password) < 8){
        $errors = true;
        sendErrorResponse('400-5', 'Password must be at least 8 chars!');
    }else{
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    }
    if(emailExists($email, $pdo)){
        $errors = true;
        sendErrorResponse('400-2', 'Introduced email it`s already in use!');
    }
    if(userExists($username, $pdo)){
        $errors = true;
        sendErrorResponse('400-6', 'Introduced username it`s already in use!');
    }

    $sql = "INSERT INTO Users (typeUser, email, firstName, lastName, pass, username)
    VALUES (:typeUser, :email, :firstName, :lastName, :pass, :username)";
    $stm = $pdo ->prepare($sql);
    $stm->bindParam(':typeUser', $typeUser, PDO::PARAM_STR);
    $stm->bindParam(':email', $email, PDO::PARAM_STR);
    $stm->bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $stm->bindParam(':lastName', $lastName, PDO::PARAM_STR);
    $stm->bindParam(':pass', $password_hashed, PDO::PARAM_STR);
    $stm->bindParam(':username', $username, PDO::PARAM_STR);
    $stm->execute();

    createACPE($typeUser, $email, $pdo);

    // print_r("Sucesso");
    sendSuccessResponse('200', 'Successful registration!', '');

} else if($route == 'login') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    // $typeUser = isset($requestData['typeUser']) ? $requestData['typeUser'] : '';
    $user = isset($requestData['user']) ? filter_var($requestData['user']) : '';
    $pass = isset($requestData['pass']) ? $requestData['pass'] : '';

    $errors = false;
    if($user == ''){
        $errors = true;
        sendErrorResponse('400', 'You must introduce Username/Email!'); 
    }
    if($pass == ''){
        $errors = true;
        sendErrorResponse('400', 'Password must be introduced!'); 
    }

    $password_hashed = password_hash($pass, PASSWORD_DEFAULT);
    checkUser($user, $pass, $pdo);


} else if($route == 'loginWeb') {
    $requestData = json_decode(file_get_contents("php://input"), true);
    if ($requestData === null) {
        // Handle JSON decoding error
        sendErrorResponse('400', 'Invalide JSON data');
        exit;
    }

    $email = isset($requestData['email']) ? filter_var($requestData['email']) : '';
    // $user = isset($requestData['user']) ? $requestData['user'] : '';
    $pass = isset($requestData['password']) ? $requestData['password'] : '';

    $errors = false;
    if($email == ''){
        $errors = true;
        sendErrorResponse('400', 'You must introduce Username/Email!'); 
    }
    if($pass == ''){
        $errors = true;
        sendErrorResponse('400', 'Password must be introduced!'); 
    } else {
        $password_hashed = password_hash($pass, PASSWORD_DEFAULT);
        checkUserWeb($email, $pass, $pdo);
    }    

} else {
    // Handle 404 Not Found
    http_response_code(404);
    echo 'Route not found!';  
}