<?php

include_once '../config/header.php';
include_once '../clases/cAdministradores.php';

$rec = new Administradores($conn->dbh);

switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST': // Crear
    try {
      $rec->crear($post['data']);
    } catch (Exception $e) {
      header('HTTP/1.0 400 Bad Request');
      echo $e->getMessage();
    }

    break;


  case 'GET': // Obtener alumno/s
    echo 'GET!';
    break;


  case 'PUT': // Actualizar
    echo 'PUT!';
    break;

    
  case 'DELETE': // Eliminar
    echo 'DELETE!';
    break;
}


/* switch ($post['accion']) {
  case 'agregar':
    if ($datos['nombre'] == '' || $datos['usuario'] == '' || $datos['password'] == '') {
      
      $res['estado'] = 'error';
      $res['err_id'] = '1';
      $res['msg'] = 'campos vacios';
      
    } else {
      
      Administradores::agregar($conn->dbh, $datos);
      
      $res['estado'] = 'ok';
      $res['msg'] = 'Alumno agregado';

    }
    
    echo json_encode($res);
    break;
} */

?>