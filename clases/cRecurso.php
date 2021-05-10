<?php

/*

En el archivo estructuras.json se definen los campos que debe recibir la API
Dentro del primer nivel se encuentra el nombre de los recursos:

  {
    "usuarios": {...},
    "reactivos": {...},
    "categorias": {...},
    ...
  }

En el segundo nivel estan los campos del recurso:

  {
    "usuarios": {
      "usuario": ...,
      "password": ...,
      "nombres": ...,
      ...
    },
    ...
  }

Si el valor de un campo es TRUE, significa que es obligatorio
Si es cualquier otro tipo que no sea booleano, sera su valor por defecto en caso de no ser especificado

Esto es posible debido a que MySQL maneja los booleanos como 1 y 0 (TinyINT) en vez de TRUE o FALSE (en realidad BOOL no existe en MySQL)
Dejando libre un tipo de dato para hacer la verificacion de si el campo es requerido
Y evitar que el archivo se vuelva complejo creando otro nivel en la estructura

*/

class Recurso {
  private $dbh;
  private $tabla;
  private $estructuras;
  
  public function __construct($dbh, $tabla) {
    $this->dbh = $dbh;
    $this->tabla = $tabla;
    $this->estructuras = json_decode(file_get_contents('../config/estructuras.json'), true);
  }

  private function validarEstructura($arr) {
    $st = $this->estructuras[$this->tabla];
    
    foreach ($st as $key => &$val) {
      if (!array_key_exists($key, $arr)) {
        if ($st[$key] !== true) {
          $arr[$key] = $st[$key];
        } else {
          throw new Exception('Campos incompletos');
        }
      }
    }

    return $arr;
  }

  private function limpiarEstructura($arr) {
    $st = $this->estructuras[$this->tabla];

    foreach ($arr as $key => &$val) {
      if (!array_key_exists($key, $st)) {
        unset($arr[$key]);
      }
    }

    return $arr;
  }

  private function consulta($query, $campos) {
    $stmt = $this->dbh->prepare($query);

    foreach ($campos as $k => &$v) {
      $stmt->bindParam(':'.$k, $v);
    }

    $stmt->execute();

    return $this->dbh->lastInsertId();
  }

  private function consultaDevolver($query, $campos = []) {
    $stmt = $this->dbh->prepare($query);

    foreach ($campos as $k => &$v) {
      $stmt->bindParam(':'.$k, $v);
    }

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();

    $res = [];

    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
      /* $obj = [];
      foreach ($row as $key => &$val) {
        $obj[$key] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $val);
      } */

      array_push($res, $row);
    }
    return $res;
  }

  // ACCIONES

  public function crear($datos) {
    if (!isset($datos)) {
      throw new Exception('Campos incompletos');
    }

    $datos = $this->validarEstructura($datos);
    $datos = $this->limpiarEstructura($datos);

    // Crear string de consulta SQL dinamicamente
    $arrSize = count($datos);
    $i = 0;

    
    $query = "INSERT INTO $this->tabla (";

    foreach ($datos as $key => &$val) {
      $query .= "$key";
      $query .= ($i < $arrSize - 1) ? ', ' : ')';
      $i++;
    }


    $query .= " VALUES (";

    $i = 0;
    foreach ($datos as $key => &$val) {
      $query .= ":$key";
      $query .= ($i < $arrSize - 1) ? ', ' : ')';
      $i++;
    }
    
    return $this->consulta($query, $datos);
  }

  public function obtener($filtros = []) {
    header('Content-Type: application/json');
    
    $query = "SELECT * FROM $this->tabla";

    if (count($filtros) > 0) {
      $arrSize = count($filtros);
      $i = 0;
      
      $query .= " WHERE ";
      
      foreach ($filtros as $key => &$val) {
        $query .= "$key = :$key";
        $query .= ($i < $arrSize - 1) ? ' AND ' : '';
        $i++;
      }
    }

    return $this->consultaDevolver($query, $filtros);
  }

  public function actualizar($campo, $valor, $datos) {
    if (!isset($datos)) {
      throw new Exception('Campos incompletos');
    }
    
    $st = $this->estructuras[$this->tabla];

    $datos = $this->limpiarEstructura($datos);

    if (count($datos) == 0) {
      throw new Exception("No hay ningun dato");
    }

    // Crear string de consulta SQL dinamicamente
    $arrSize = count($datos);
    $i = 0;

    $query = "UPDATE $this->tabla SET ";
    foreach ($datos as $key => &$val) {
      $query .= "$key = :$key";
      $query .= ($i < $arrSize - 1) ? ', ' : ' ';
      $i++;
    }
    $query .= "WHERE $campo = :val";
    
    $datosQuery = array_merge($datos, ['val' => $valor]);
    $this->consulta($query, $datosQuery);
  }

  public function eliminar($campo, $valor) {
    $query = "DELETE FROM $this->tabla WHERE $campo = :val";

    $this->consulta($query, ['val' => $valor]);
  }
}

?>