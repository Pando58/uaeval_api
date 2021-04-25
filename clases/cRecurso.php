<?php

class Recurso {
  private $dbh;
  private $tabla;
  private $estructuras;
  
  public function __construct($dbh, $tabla) {
    $this->dbh = $dbh;
    $this->tabla = $tabla;
    $this->estructuras = json_decode(file_get_contents('../config/estructuras.json'), true);
  }

  public function validarEstructura($arr) {
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
      array_push($res, $row);
    }

    return $res;
  }

  // ACCIONES

  public function crear($datos) {
    $datos = $this->validarEstructura($datos);

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
    
    $this->consulta($query, $datos);
  }

  public function obtener() {}

  public function obtenerTodos() {}

  public function actualizar() {}

  public function eliminar() {}
}

?>