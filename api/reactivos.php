<?php

include_once '../config/header.php';
include_once '../clases/cRecurso.php';
include_once '../auth/auth.php';

/* if (AuthToken::obtenerDatosToken($jwt)->data->admin != 1) {
  header('HTTP/1.1 401 Unauthorized');
  echo 'No hay suficientes permisos';
  exit;
} */

if (($gestor = fopen("alumnos.csv", "r")) !== false) {

  while (($datos = fgetcsv($gestor, 1000, ",")) !== false) {
    $query = "INSERT INTO usuarios (usuario, nombres, id_grupo) VALUES (:a, :b, :c)";
    hacerConsulta($conn->dbh, $query, $datos);
  }

  fclose($gestor);
}

function hacerConsulta($conn, $query, $datos) {
  $stmt = $conn->prepare($query);

  $stmt->bindParam(':a', $datos[0]);
  $stmt->bindParam(':b', $datos[1]);
  $stmt->bindParam(':c', $datos[2]);

  $stmt->execute();
}

exit;

$rec = new Recurso($conn->dbh, 'reactivos');

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