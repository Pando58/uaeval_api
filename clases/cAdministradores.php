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

    $datosQuery = array_merge(['id_usuario' => $insID], $datos['permisos']);
    parent::consulta($query, $datosQuery);
  }

  public function obtenerTodos() {
    header('Content-Type: application/json');
    
    $query = "SELECT * FROM usuarios WHERE es_administrador = 1";
    echo json_encode(parent::consultaDevolver($query));
  }

  public function obtener($id) {
    header('Content-Type: application/json');

    $query = "SELECT * FROM usuarios WHERE es_administrador = 1 AND id = :id";
    echo json_encode(parent::consultaDevolver($query, ['id' => $id]));
  }

  public function actualizar($id, $datos) {
    $query = "UPDATE usuarios SET ";
    foreach ($datos as $k => &$v) {
      $query .= "$k = :$k";
      $query .= ($v != end($datos)) ? ', ' : ' ';
    }
    $query .= "WHERE id = :id";

    if (isset($datos['password'])) {
      $datos['password'] = password_hash($datos['password'], PASSWORD_BCRYPT);
    }

    $datosQuery = array_merge($datos, ['id' => $id]);
    parent::consulta($query, $datosQuery);
  }

  public function eliminar($id) {
    $query = "DELETE FROM usuarios WHERE id = :id";

    parent::consulta($query, ['id' => $id]);
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