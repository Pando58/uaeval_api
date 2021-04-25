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
      array_push($res, $row);
    }

    return $res;
  }

  // ACCIONES

  public function crear($datos) {
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

  public function obtener($campo, $valor) {
    header('Content-Type: application/json');

    $query = "SELECT * FROM $this->tabla WHERE $campo = :val";
    return $this->consultaDevolver($query, ['val' => $valor]);
  }

  public function obtenerTodos() {
    header('Content-Type: application/json');
    
    $query = "SELECT * FROM $this->tabla";
    return $this->consultaDevolver($query);
  }

  public function actualizar($campo, $valor, $datos) {
    $st = $this->estructuras[$this->tabla];

    $datos = $this->limpiarEstructura($datos);

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