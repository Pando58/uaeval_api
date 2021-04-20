<?php

require_once '../config/header.php';
require_once '../config/status.php';
require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;

$user = $post['usuario'];
$pass = $post['password'];

$res;

if (!$user || !$pass) {
  $res = status::error(1, 'campos incompletos');
} else {
  $usuario = funciones::obtenerUsuario($conn, $user);
  
  if ($usuario == null) {
    $res = status::error(2, 'usuario inexistente');
  } else {
    if (!password_verify($pass, $usuario['password'])) {
      $res = status::error(3, 'password incorrecto');
    } else {
      // Logear
    }
  }
}


/* 

// Crear token
  $key = 'Reforma2021725';
  $time = time();

  $token = array(
    'iat' => $time, // Hora de inicio del token
    'exp' => $time + (60*60), // Hora en la que expirara el token (1 hora)
    'data' => [ // Informacion del usuario
      'id' => '', 'usuario' => ''
    ]
  );

  $jwt = JWT::encode($token, $key);

  // Respuesta
  $res = status::ok(array(
    'token' => $jwt
  ));

*/

echo json_encode($res);

?>