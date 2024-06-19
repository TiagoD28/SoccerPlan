<?php
// Define your $db array with connection details
// $db = [
//     'host' => 'localhost',
//     'port' => '3306',
//     'charset' => 'utf8',
//     'dbname' => 'soccerplan',
//     'username' => 'root',
//     'password' => ''
// ];

$guru = '30';

// $dsg_dbo = [
//     'host' => 'mysql-sa.mgmt.ua.pt',
//     'port' => '3306',
//     'charset' => 'utf8',
//     'dbname' => 'esan-dsg' . $guru,
//     'username' => 'esan-dsg' . $guru . '-dbo',
//     # COLOCAR PASSWORD DBO
//     'password' => 'bx@N5rmCcV(T87-Q'
// ];

$dsg_web = [
    'host' => 'mysql-sa.mgmt.ua.pt',
    'port' => '3306',
    'charset' => 'utf8',
    'dbname' => 'esan-dsg' . $guru,
    'username' => 'esan-dsg' . $guru . '-web',
    # COLOCAR PASSWORD DBO
    'password' => 'D.uXbcoUt30PG)Lt'
];

$db = $dsg_web;

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