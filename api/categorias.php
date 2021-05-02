<?php

include_once '../config/header.php';
include_once '../clases/cRecurso.php';
include_once '../auth/auth.php';

/* if (AuthToken::obtenerDatosToken($jwt)->data->admin != 1) {
  header('HTTP/1.1 401 Unauthorized');
  echo 'No hay suficientes permisos';
  exit;
} */

$rec = new Recurso($conn->dbh, 'categorias');

switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST':
    try {
      $rec->crear($post);
    } catch (Exception $e) {
      header('HTTP/1.0 400 Bad Request');
      echo $e->getMessage();
    }
    break;

  case 'GET':
    if (isset($_GET['id'])) {
      echo json_encode($rec->obtener(['id' => $_GET['id']]));
    } else {
      echo json_encode($rec->obtener());
    }
    break;

  case 'PUT':
    if (!isset($_GET['id'])) {
      header('HTTP/1.0 400 Bad Request');
      echo 'No existe un id';
      exit;
    }

    try {
      $rec->actualizar('id', $_GET['id'], $post ?? []);
    } catch (Exception $e) {
      header('HTTP/1.0 400 Bad Request');
      echo $e->getMessage();
    }
    break;

  case 'DELETE':
    if (!isset($_GET['id'])) {
      header('HTTP/1.0 400 Bad Request');
      echo 'No existe un id';
    }

    try {
      $rec->eliminar('id', $_GET['id']);
    } catch (Exception $e) {
      header('HTTP/1.0 400 Bad Request');
      echo $e->getMessage();
    }
    break;
}