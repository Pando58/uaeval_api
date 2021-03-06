<?php

$conf = json_decode(file_get_contents('../config/server_config.json'));

header("Access-Control-Allow-Origin: " . ($conf->cors ?? 'http://localhost:3000'));
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, pragma, cache-control");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

require_once '../vendor/autoload.php';
include_once '../clases/funciones.php';
include_once '../clases/conexionDB.php';
require_once '../clases/cAuth.php';

$post = json_decode(file_get_contents('php://input'), true);

$conn = new ConexionDB();

/* if (!funciones::revisarEstadoLogin($conn, $datos['auth'])) {
  header('HTTP/1.1 401 Unauthorized');
  header('WWW-Authenticate: Basic realm="Acceso a API"');
  echo('No tienes las suficientes credenciales');
  exit;
} */

?>