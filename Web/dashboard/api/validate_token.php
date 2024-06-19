<?php

// Carregar configurações
require_once '../../config.php';
$pdo = connectDB($db);

// Carregar classe
require_once '../../objects/User.php';
$user = new User($pdo);

// Carregar JWT
require '../../vendor/autoload.php';

use \Firebase\JWT\JWT;

// Definição do cabeçalho
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, "
        . "Access-Control-Allow-Headers, "
        . "Authorization, X-Requested-With");

/*
// Obter dados do POST
#$data = ...

// Obter JWT
$jwt = isset($data->jwt) ? $data->jwt : "";
if ($jwt) {
    try {
        // Decode do JWT
#        $decoded = ...
        // Sucesso na operação - 200 OK
#        ...
        // Enviar Resposta
#        echo ...
#            "message" => ...
#            "data" => ...
        ));
    } catch (Exception $e) {
    // Acesso negado - 401 Unauthorized
#   ...
        // Enviar Resposta
#        echo ...
#            "message" => ...
#            "error" => ...
        ));
    }
} else {
    // Acesso negado - 401 Unauthorized
#    ...
    // Enviar Resposta
#    echo ...
}
 * 
 */