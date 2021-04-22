<?php

require_once '../config/header.php';
require_once '../clases/status.php';

$user = $post['usuario'];
$pass = $post['password'];
$admin = $post['admin'];

$res;

if (!$user || !$pass) {
  $res = status::error(1, 'campos incompletos');
} else {
  $usuario = funciones::obtenerUsuario($conn->dbh, $user);
  
  if ($usuario == null) {
    $res = status::error(2, 'usuario inexistente');
  } else {
    if (!password_verify($pass, $usuario['password'])) {
      $res = status::error(3, 'datos de ingreso incorrectos');
    } else if ($admin != $usuario['es_administrador']) {
      $res = status::error(4, 'la propiedad de administrador no coincide');
    } else {
      // Login valido - enviar token
      $res = status::ok(array(
        'token' => Auth::generarToken([
          'id' => $usuario['id'],
          'usuario' => $usuario['usuario'],
          'admin' => $usuario['es_administrador']
        ])
      ));
    }
  }
}

echo json_encode($res);

?>