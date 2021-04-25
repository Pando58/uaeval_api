<?php

include_once '../clases/cRecurso.php';

class Administradores extends Recurso {
  public function crear($datos) {
    $this->validarEstructura($datos);
    
    $query = "
      INSERT INTO usuarios (
        usuario,
        nombres,
        apellido_p,
        apellido_m,
        password,
        es_administrador
      ) VALUES (
        :usuario,
        :nombres,
        :apellido_p,
        :apellido_m,
        :password,
        true
      )
    ";

    $insID = parent::consulta($query, [
      'usuario' => $datos['usuario'],
      'password' => password_hash($datos['password'], PASSWORD_BCRYPT),
      'nombres' => $datos['nombres'],
      'apellido_p' => $datos['apellido_p'],
      'apellido_m' => $datos['apellido_m']
    ]);

    $query = "
      INSERT INTO permisos (
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
      )
    ";

    parent::consulta($query, [
      'id_usuario' => $insID,
      'alumnos_editar' => $datos['permisos']['alumnos_editar'],
      'administradores_editar' => $datos['permisos']['administradores_editar'],
      'grupos_editar' => $datos['permisos']['grupos_editar'],
      'docentes_editar' => $datos['permisos']['docentes_editar'],
      'categorias_editar' => $datos['permisos']['categorias_editar'],
      'reactivos_editar' => $datos['permisos']['reactivos_editar']
    ]);
  }

  public function obtener() {
    parent::obtener();
    
    echo json_encode(parent::consultaDevolver("SELECT * FROM usuarios WHERE es_administrador = 1"));
  }

  protected function validarEstructura($arr) {
    if (
      !isset($arr['usuario']) ||
      !isset($arr['password']) ||
      !isset($arr['nombres']) ||
      !isset($arr['apellido_p']) ||
      !isset($arr['apellido_m']) ||
      !isset($arr['permisos'])
    ) {
      throw new Exception('Campos incompletos');
    }

    if (
      !isset($arr['permisos']['alumnos_editar']) ||
      !isset($arr['permisos']['administradores_editar']) ||
      !isset($arr['permisos']['grupos_editar']) ||
      !isset($arr['permisos']['docentes_editar']) ||
      !isset($arr['permisos']['categorias_editar']) ||
      !isset($arr['permisos']['reactivos_editar'])
    ) {
      throw new Exception('Campos incompletos');
    }
  }
}

?>