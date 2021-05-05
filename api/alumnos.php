<?php

include_once '../config/header.php';
include_once '../clases/cRecurso.php';
include_once '../auth/auth.php';

if (AuthToken::obtenerDatosToken($jwt)->data->admin != 1) {
  header('HTTP/1.1 401 Unauthorized');
  echo 'No hay suficientes permisos';
  exit;
}

$rec = new Recurso($conn->dbh, 'usuarios');

switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST': // Crear
    $post['es_administrador'] = 0;

    $post['password'] = '';

    if (!isset($post['id_grupo'])) {
      header('HTTP/1.0 400 Bad Request');
      echo 'Campos incompletos';
      exit;
    }
    
    try {
      $rec->crear($post);
    } catch (Exception $e) {
      header('HTTP/1.0 400 Bad Request');
      echo $e->getMessage();
      exit;
    }

    break;


  case 'GET': // Obtener alumno/s
    if (isset($_GET['id'])) {
      echo json_encode($rec->obtener(['id' => $_GET['id'], 'es_administrador' => 0]));
    } else {
      echo json_encode($rec->obtener(['es_administrador' => 0]));
    }

    break;


  case 'PUT': // Actualizar
    if (isset($post['password']) && $post['password'] !== '') {
      $post['password'] = password_hash($post['password'], PASSWORD_BCRYPT);
    }

    if (!isset($_GET['id'])) {
      header('HTTP/1.0 400 Bad Request');
      echo 'No existe un id';
    } else {

      try {
        $rec->actualizar('id', $_GET['id'], $post);
      } catch (Exception $e) {
        header('HTTP/1.0 400 Bad Request');
        echo $e->getMessage();
      }

    }

    break;

    
  case 'DELETE': // Eliminar
    if (!isset($_GET['id'])) {
      header('HTTP/1.0 400 Bad Request');
      echo 'No existe un id';
    } else {

      // Eliminar usuario
      try {
        $rec->eliminar('id', $_GET['id']);
      } catch (Exception $e) {
        header('HTTP/1.0 400 Bad Request');
        echo $e->getMessage();
      }

    }

    break;
}

?>