<?php

abstract class Recurso {
  private $dbh;
  
  public function __construct($dbh) {
    $this->dbh = $dbh;
  }  
  
  public function crear($datos) {}
  public function obtener() {
    header('Content-Type: application/json');
  }
  public function obtenerTodos() {
    header('Content-Type: application/json');
  }
  public function editar() {}
  public function remover() {}

  protected function consulta($query, $campos) {
    $stmt = $this->dbh->prepare($query);

    foreach ($campos as $k => &$v) {
      $stmt->bindParam(':'.$k, $v);
    }

    $stmt->execute();

    return $this->dbh->lastInsertId();
  }

  protected function consultaDevolver($query, $campos = []) {
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

  protected function validarEstructura($arr) {}
}

?>