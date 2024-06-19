<?php
// Define your $db array with connection details
$db = [
    'host' => 'localhost',
    'port' => '3306',
    'charset' => 'utf8',
    'dbname' => 'soccerplan',
    'username' => 'root',
    'password' => ''
];

try {
    $pdo = new PDO(
        'mysql:host=' . $db['host'] . ';port=' . $db['port'] . ';charset=' . $db['charset'] . ';dbname=' . $db['dbname'],
        $db['username'],
        $db['password']
    );
} catch (PDOException $e) {
    die('Erro ao ligar ao servidor ' . $e->getMessage());
}

$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// <!-- <?php
// // Define your $db array with connection details
//  $db = [
//     'host' => 'localhost',
//      'port' => '3306',
//      'charset' => 'utf8',
//      'dbname' => 'soccerplan',
//      'username' => 'root',
//      'password' => ''
//  ];

// try {
//     $pdo = new PDO(
//         'mysql:host=' . $db['host'] . '; ' .
//         'port=' . $db['port'] . ';' .
//         'charset=' . $db['charset'] . ';' .
//         'dbname=' . $db['dbname'] . ';' ,
//         $db['username'],
//         $db['password']

//     );
// } catch (PDOException $e) {
//     die('Erro ao ligar ao servidor ' . $e->getMessage());
// }

// $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// // $sql = "SELECT nome, idade FROM Jogadores";
// // $stm = $pdo->prepare($sql);
// // $stm->bindValue(':ID', $id);
// // $result = $stm->execute();

// // $data = $stm->fetchAll(PDO::FETCH_ASSOC);
// header('Content-Type: application/json');

//     echo json_encode([
//         'status' => '200',
//         'message' => 'sucesso',
//         'data' => $data
//     ]); -->