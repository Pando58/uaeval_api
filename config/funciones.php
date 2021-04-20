<?php

class funciones {
  public static function obtenerUsuario($dbh, $usuario) {
    $query = 'SELECT * FROM usuarios WHERE usuario = :usuario';

    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      return $row;
    } else {
      return null;
    }
  }
}

?>