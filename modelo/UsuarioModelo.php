<?php
require_once __DIR__ . '/Conexion.php';

class UsuarioModelo {
    private $conexion;

    public function __construct() {
        // Se establece la conexión a la base de datos
        $this->conexion = (new Conexion())->conectar();
    }

    /**
     * Registrar un nuevo usuario en la base de datos.
     * 
     * Campos requeridos:
     * - nombre_usuario
     * - apellido1
     * - apellido2
     * - genero
     * - tipo_documeto
     * - numero_documento
     * - correo_usuario
     * - usuario_login
     * - password_hash
     * - rol (por defecto: 'Usuario')
     */
    public function registrar($nombre, $apellido1, $apellido2, $genero, $tipo_documeto, $numero_documento, $correo, $usuario, $password) {
        // Encriptar contraseña antes de guardarla
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Consulta SQL para insertar nuevo usuario
        $sql = "INSERT INTO tb_usuario (
                    nombre_usuario, apellido1, apellido2, genero, tipo_documeto, 
                    numero_documento, correo_usuario, usuario_login, password_hash, rol
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Usuario')";

        // Preparar consulta para evitar inyecciones SQL
        $stmt = $this->conexion->prepare($sql);

        // Enlazar los parámetros (9 valores tipo string)
        $stmt->bind_param(
            "sssssssss", 
            $nombre, 
            $apellido1, 
            $apellido2, 
            $genero, 
            $tipo_documeto, 
            $numero_documento, 
            $correo, 
            $usuario, 
            $hash
        );

        // Ejecutar y devolver resultado (true/false)
        return $stmt->execute();
    }

    /**
     * Iniciar sesión
     * Verifica las credenciales del usuario y devuelve sus datos si son válidas.
     */
    public function login($usuario, $password) {
        $sql = "SELECT * FROM tb_usuario WHERE usuario_login = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verificar contraseña cifrada
        if ($user && password_verify($password, $user['password_hash'])) {
            $this->actualizarUltimoLogin($user['id_usuario']);
            return $user;
        }

        return false;
    }

    /**
     * Actualiza la fecha del último inicio de sesión del usuario.
     */
    private function actualizarUltimoLogin($idUsuario) {
        $sql = "UPDATE tb_usuario SET fecha_ultimo_login = NOW() WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
    }
}
?>
