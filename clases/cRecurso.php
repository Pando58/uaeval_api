<?php

abstract class Recurso {
  private $dbh;
  
  public function __construct($dbh) {
    $this->dbh = $dbh;
  }  
  
  public function crear($datos) {}
  public function obtener() {}
  public function obtenerTodos() {}
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

  // protected function consultaDevolver($query, $campos) {

  // }

  protected function validarEstructura($arr) {}
}

?>