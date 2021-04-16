<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

$post = json_decode(file_get_contents('php://input'), true);
$res;

switch ($post['accion']) {
  case 'agregar':
    $d = $post['datos'];
    if ($d['nombre'] == '' || $d['usuario'] == '' || $d['password'] == '') {
      
      $res['estado'] = 'error';
      $res['err_id'] = '1';
      $res['msg'] = 'campos vacios';
      
    } else {
      
      $res['estado'] = 'ok';
      $res['msg'] = 'Alumno agregado';

    }
    
    echo json_encode($res);
    break;
}




/* switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST': // Agregar
    $_POST = json_decode(file_get_contents('php://input'), true);
    
    $res['msg'] = 'Agregar alumno: ' . json_encode($_POST);
    
    echo json_encode($res);
    break;


  case 'GET': // Obtener alumno/s
    $res;
    
    if (isset($_GET['id'])) {
      $res['msg'] = 'Devolver alumno con id: ' . $_GET['id'];
    } else {
      $res['msg'] = 'Devolver todos los usuarios';
    }

    echo json_encode($res);
    break;


  case 'PUT': // Actualizar
    $_PUT = json_decode(file_get_contents('php://input'), true);
    
    $res['msg'] = 'Actualizar alumno con id: ' . $_GET['id'] . ', Informacion: ' . json_encode($_PUT);

    echo json_encode($res);
    break;

    
  case 'DELETE': // Eliminar
    $res['msg'] = 'Eliminar alumno con el id' . $_GET['id'];

    echo json_encode($res);
    break;
} */

?>