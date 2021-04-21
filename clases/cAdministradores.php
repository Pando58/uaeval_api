<?php

class Administradores {
  public static function agregar($dbh, $datos) {
    // Usuario
    $query = 'INSERT INTO usuarios (usuario, nombres, apellido_p, apellido_m, password, es_administrador) VALUES (:usuario, :nombres, :ap_p, :ap_m, :pass, true)';
      
    $hash_pass = password_hash($datos['password'], PASSWORD_DEFAULT);
    
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':usuario', $datos['usuario']);
    $stmt->bindParam(':nombres', $datos['nombre']);
    $stmt->bindParam(':ap_p', $datos['apellido_p']);
    $stmt->bindParam(':ap_m', $datos['apellido_m']);
    $stmt->bindParam(':pass', $hash_pass);
    $stmt->execute();
    
    // Permisos
    $query = 'INSERT INTO permisos (
      id_usuario,
      alumnos_editar,
      administradores_editar,
      grupos_editar,
      docentes_editar,
      categorias_editar,
      reactivos_editar
    ) VALUES (
      :id_usuario,
      :alumnos_editar,
      :administradores_editar,
      :grupos_editar,
      :docentes_editar,
      :categorias_editar,
      :reactivos_editar
    )';
    
    $insID = $dbh->lastInsertId();
    
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id_usuario', $insID);
    $stmt->bindParam(':alumnos_editar', $datos['permisos']['alumnos_editar']);
    $stmt->bindParam(':administradores_editar', $datos['permisos']['administradores_editar']);
    $stmt->bindParam(':grupos_editar', $datos['permisos']['grupos_editar']);
    $stmt->bindParam(':docentes_editar', $datos['permisos']['docentes_editar']);
    $stmt->bindParam(':categorias_editar', $datos['permisos']['categorias_editar']);
    $stmt->bindParam(':reactivos_editar', $datos['permisos']['reactivos_editar']);
    $stmt->execute();
  }

  public static function editar() {}

  public static function remover() {}
  
  public static function obtener() {}

  public static function obtenerTodos() {}
}

?>