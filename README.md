# uaeval_api
API del sistema de evaluación docente para la Universidad Alva Edison.

------------

### **Recursos**
  * /alumnos
  * /docentes
  * /grupos
  * /reactivos
  * /categorias
  * /administradores
  * /permisos
  <!--  -->
  * /cuestionarios **[`GET`]**
    - Total de cuestionarios (Numero de alumnos)
    - Numero de cuestionarios completados
    - Numero de cuestionarios sin terminar

------------

### **Métodos**
Acción | Método HTTP | Ruta
------ | ----------- | ----
Crear            | **`POST`**   | `/ruta`
Obtener (uno)    | **`GET`**    | `/ruta/{id}`
Obtener (varios) | **`GET`**    | `/ruta`
Actualizar       | **`PUT`**    | `/ruta/{id}`
Eliminar         | **`DELETE`** | `/ruta/{id}`