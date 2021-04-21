<?php

require_once '../config/header.php';
require_once '../clases/status.php';
require_once '../clases/auth.php';
require_once '../vendor/autoload.php';

$user = $post['usuario'];
$pass = $post['password'];

$res;

if (!$user || !$pass) {
  $res = status::error(1, 'campos incompletos');
} else {
  $usuario = funciones::obtenerUsuario($conn->dbh, $user);
  
  if ($usuario == null) {
    $res = status::error(2, 'usuario inexistente');
  } else {
    if (!password_verify($pass, $usuario['password'])) {
      $res = status::error(3, 'password incorrecto');
    } else {
      // Login valido - enviar token
      $res = status::ok(array(
        'token' => Auth::login([
          'id' => $usuario['id'],
          'usuario' => $usuario['usuario']
        ])
      ));
    }
  }
}

echo json_encode($res);

?>