<?php

require_once '../config/header.php';
include_once '../clases/cRecurso.php';

// Evitar error de CORS
if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
  header("HTTP/1.1 200 OK");
  exit;
}


try {
  if (!isset($post['matricula'])) {
    throw new Exception('datos faltantes');
  }

  $usuario = funciones::obtenerUsuario($conn->dbh, $post['matricula']);
  
  if (!$usuario) {
    throw new Exception('no existe el usuario', 2);
  }

  if ($usuario['password'] !== '') {
    throw new Exception('el usuario ya tiene contraseña', 3);
  }
  
  if (isset($post['password'])) {
    if ($post['password'] == '') {
      throw new Exception('campos incompletos', 4);
    }

    if (strlen($post['password']) < 10) {
      throw new Exception('contraseña corta', 5);
    }

    $hashedPassword = password_hash($post['password'], PASSWORD_BCRYPT);

    $rec = new Recurso($conn->dbh, 'usuarios');
    $rec->actualizar('usuario', $post['matricula'], ['password' => $hashedPassword]);
  }

} catch (Exception $e) {
  header('HTTP/1.0 400 Bad Request');
  header('Content-Type: application/json');
  echo json_encode([
    'msg' => $e->getMessage(),
    'code' => $e->getCode()
  ]);
}

?>