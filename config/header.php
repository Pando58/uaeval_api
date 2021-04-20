<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

include_once 'funciones.php';
include_once 'db_conn.php';

$post = json_decode(file_get_contents('php://input'), true);

$conn = new ConexionDB();

/* if (!funciones::revisarEstadoLogin($conn, $datos['auth'])) {
  header('HTTP/1.1 401 Unauthorized');
  header('WWW-Authenticate: Basic realm="Acceso a API"');
  echo('No tienes las suficientes credenciales');
  exit;
} */

?>