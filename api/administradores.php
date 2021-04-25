<?php

include_once '../config/header.php';
include_once '../clases/cRecurso.php';

$rec = new Recurso($conn->dbh, 'usuarios');
$rec_perms = new Recurso($conn->dbh, 'permisos');

switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST': // Crear
    $post['es_administrador'] = 1;

    if (isset($post['password'])) {
      $post['password'] = password_hash($post['password'], PASSWORD_BCRYPT);
    }

    $insID;
    
    // Crear usuario
    try {
      // Almacenar el id insertado para ponerlo en la tabla de permisos
      $insID = $rec->crear($post);
    } catch (Exception $e) {
      header('HTTP/1.0 400 Bad Request');
      echo $e->getMessage();
      exit;
    }

    // Crear permisos
    try {
      $datos = array_merge(['id_usuario' => $insID], $post['permisos']);
      $rec_perms->crear($datos);
    } catch (Exception $e) {
      header('HTTP/1.0 400 Bad Request');
      echo $e->getMessage();
    }

    break;


  case 'GET': // Obtener alumno/s
    if (isset($_GET['id'])) {
      echo json_encode($rec->obtener('id', $_GET['id']));
    } else {
      echo json_encode($rec->obtenerTodos());
    }

    break;


  case 'PUT': // Actualizar
    if (isset($post['password'])) {
      $post['password'] = password_hash($post['password'], PASSWORD_BCRYPT);
    }

    if (!isset($_GET['id'])) {
      header('HTTP/1.0 400 Bad Request');
      echo 'No existe un id';
    } else {

      try {
        $rec->actualizar('id', $_GET['id'], $post ?? []);
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

      // Eliminar entrada en permisos
      try {
        $rec_perms->eliminar('id_usuario', $_GET['id']);
      } catch (Exception $e) {
        header('HTTP/1.0 400 Bad Request');
        echo $e->getMessage();
      }

    }

    break;
}

?>