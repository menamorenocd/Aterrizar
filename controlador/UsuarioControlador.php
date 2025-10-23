<?php
require_once __DIR__ . '/../modelo/UsuarioModelo.php';

class UsuarioControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new UsuarioModelo();
    }

    /**
     * Registrar usuario normal (por defecto con rol 'Usuario')
     */
    public function registrar($nombre, $apellido1, $apellido2, $genero, $tipo_documeto, $numero_documento, $correo, $usuario, $password) {
        if ($this->modelo->registrar($nombre, $apellido1, $apellido2, $genero, $tipo_documeto, $numero_documento, $correo, $usuario, $password)) {
            header("Location: login.php");
            exit;
        } else {
            return "Error al registrar usuario.";
        }
    }

    /**
     * Iniciar sesión
     * Dependiendo del rol del usuario redirige a diferentes vistas
     */
    public function login($usuario, $password) {
        $user = $this->modelo->login($usuario, $password);

        if ($user) {
            // Guardar los datos del usuario en sesión
            $_SESSION['usuario'] = [
                'id' => $user['id_usuario'],
                'nombre' => $user['nombre_usuario'],
                'rol' => $user['rol'],
                'login' => $user['usuario_login']
            ];

            // Redirigir según el rol
            switch ($user['rol']) {
                case 'Administrador':
                    header("Location: ../vista/admin/panel_admin.php");
                    break;
                case 'ADS':
                    header("Location: ../vista/superadmin/panel_superadmin.php");
                    break;
                default: // Usuario normal
                    header("Location: ../vista/usuario/panel_usuario.php");
                    break;
            }
            exit;
        } else {
            return "Usuario o contraseña incorrectos.";
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: ../vista/login.php");
        exit;
    }
}
?>
