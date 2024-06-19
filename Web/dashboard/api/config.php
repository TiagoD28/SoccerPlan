<?php
/* Descrição: Configurações da aplicação
 * Autor: Mário Pinto
 * 
 */
$guru='30';
$dsg_dbo = [
    'host' => 'mysql-sa.mgmt.ua.pt',
    'port' => '3306',
    'charset' => 'utf8',    
    'dbname' => 'esan-dsg'.$guru,
    'username' => 'esan-dsg'.$guru.'-dbo',
    'password' => 'bx@N5rmCcV(T87-Q'
];
$dsg_web = [
    'host' => 'mysql-sa.mgmt.ua.pt',
    'port' => '3306',
    'charset' => 'utf8',
    'dbname' => 'esan-dsg'.$guru,
    'username' => 'esan-dsg'.$guru.'-web',
    'password' => 'D.uXbcoUt30PG)Lt'
];

$db = [
    'host' => 'localhost',
    'port' => '3306',
    'charset' => 'utf8',
    'dbname' => 'soccerplan',
    'username' => 'root',
    'password' => ''
];


// Descomentar o utilizador pretendido: DBO ou WEB
#$db = $dsg_dbo;
// $db = $dsg_web;


// UPLOAD
define('WEB_SERVER','https://esan-tesp-ds-paw.web.ua.pt');
# Colocar grupo GURU
define('WEB_ROOT','https://tesp-ds-g30/');

define('SERVER_FILE_ROOT','//ARCA.STORAGE.UA.PT/HOSTING/esan-tesp-ds-paw.web.ua.pt'.WEB_ROOT);
define('UPLOAD_FOLDER','uploads/');

// UPLOAD_PATH - Por segurança deve estar fora do Webserver Root
define('UPLOAD_PATH',SERVER_FILE_ROOT . UPLOAD_FOLDER);

// AVATAR - Definições para o avatar dos utilizadores
define('AVATAR_FOLDER',UPLOAD_FOLDER.'avatar/');
define('AVATAR_PATH',SERVER_FILE_ROOT . AVATAR_FOLDER);
define('AVATAR_WEB_PATH',WEB_ROOT.AVATAR_FOLDER);
define('AVATAR_DEFAULT','avatar.png');

define('ATTACHMENTS_PATH',SERVER_FILE_ROOT . UPLOAD_FOLDER . 'attach/');


define('DEBUG', true);

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Autor
define('AUTHOR', 'Tiago Domingos');
define('UC', 'PAW');
define('ANO_LETIVO', '2022.2023');

/**
  Mailer:SMTP
  From email:[dep]-[nome]@ua.pt
  From Name : [nome que aparece nos e-mail enviados]
  SMTP Authentication: YES
  SMTP Security: TLS
  SMTP Port: 25
  SMPT Username: [dep]-[nome]@ua.pt
  SMTP Password: [senha de acesso à conta referida no SMTP Username]
  SMTP Host: smtp-servers.ua.pt
 * 
  Nome:       Projeto Desenvolvimento de Software | ESAN
  e-mail:     esan-tesp-ds-paw@ua.pt
  login:      esan-tesp-ds-paw@ua.pt
  password:   8ee83a66c46001b7ee7b3ee886bf8375
 */
#define('EMAIL_CHARSET','');
#define('EMAIL_ENCODING', '');
#define('EMAIL_HOST','');
#define('EMAIL_SMTPAUTH', true);
#define('EMAIL_USERNAME','');
#define('EMAIL_PASSWORD','');
#define('EMAIL_PORT', );
define('EMAIL_FROM','Projeto Desenvolvimento de Software | ESAN');


/**
 * Definições JWT
 */
$jwt_conf = [
    'key' => "abcd1234",   // chave para assinatura
    'iss' => "https://esan-tesp-ds-paw.web.ua.pt",  // servidor
    'jti' => bin2hex(random_bytes(128)), // Token ID com 256 caracteres
    'iat' => time(),
    'nbf' => time(),        // Válido imediatamente após emissão
    'exp' => time() + 3600   // Válido durante 60 minutos
];


require_once './core.php';