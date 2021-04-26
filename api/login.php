<?php

require_once '../config/header.php';
require_once '../clases/status.php';

header('Content-Type: application/json');

// Evitar error de CORS
if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
  header("HTTP/1.1 200 OK");
  exit;
}

try {

  if (
    !$post['usuario'] ||
    !$post['password']
  ) {
    throw new Exception('Campos incompletos', 1);
  }

  if (!isset($post['admin'])) {
    throw new Exception('No existe la propiedad administrador', 4);
  }
  
  $res = ['token' => login($conn, $post['usuario'], $post['password'], $post['admin'])];
  echo json_encode($res);

} catch (Exception $e) {

  if ($e->getCode() == 1) {
    header('HTTP/1.0 400 Bad Request');
  } else {
    header('HTTP/1.1 401 Unauthorized');
  }

  echo json_encode([
    'msg' => $e->getMessage(),
    'code' => $e->getCode()
  ]);
  
}

function login($conn, $user, $pass, $admin) {  
  $usuario = funciones::obtenerUsuario($conn->dbh, $user);
  
  if ($usuario == null) {
    throw new Exception('Usuario inexistente', 2);
  }
  
  if (!password_verify($pass, $usuario['password'])) {
    throw new Exception('Datos de ingreso incorrectos', 3);
  }

  if ($admin != $usuario['es_administrador']) {
    throw new Exception('La propiedad de administrador no coincide', 4);
  }

  // Login valido - enviar token
  return AuthToken::generarToken([
    'id' => $usuario['id'],
    'usuario' => $usuario['usuario'],
    'nombres' => $usuario['nombres'],
    'apellido_p' => $usuario['apellido_p'],
    'apellido_m' => $usuario['apellido_m'],
    'admin' => (int)$usuario['es_administrador']
  ]);
}

?>