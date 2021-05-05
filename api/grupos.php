<?php

include_once '../config/header.php';
include_once '../clases/cRecurso.php';
include_once '../auth/auth.php';

/* if (AuthToken::obtenerDatosToken($jwt)->data->admin != 1) {
  header('HTTP/1.1 401 Unauthorized');
  echo 'No hay suficientes permisos';
  exit;
} */

$rec = new Recurso($conn->dbh, 'grupos');

switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST':
    if (isset($post['id_docentes'])) {
      if (!validarJsonDocentes($post['id_docentes'])) {
        header('HTTP/1.0 400 Bad Request');
        echo 'Formato no valido';
        exit;
      }
    }

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

    if (isset($post['id_docentes'])) {
      if (!validarJsonDocentes($post['id_docentes'])) {
        header('HTTP/1.0 400 Bad Request');
        echo 'Formato no valido';
        exit;
      }
    }

    try {
      $rec->actualizar('id', $_GET['id'], $post);
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



function validarJsonDocentes($str) {
  $json = json_decode($str, true);

  // Si no esta bien escrito
  if (!$json) {
    return;
  }

  // Si no es un array
  if (!is_array($json)) {
    return;
  }

  // Si el array es asociativo
  if(array_keys($json) !== range(0, count($json) - 1)) {
    return;
  }

  // Si uno de los valores no es numerico
  foreach ($json as &$val) {
    if (!is_numeric($val)) {
      return;
    }
  }

  return true;
}